<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiResource\Pages;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static ?string $navigationIcon = 'tabler-checkup-list';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Nilai';

    public static ?int $navigationGroupSort = 2;

    public static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('semester_id')
                    ->label('Tahun Akademik')
                    ->required()
                    ->options(function () {
                        return Semester::join('tahun_akademiks', 'tahun_akademiks.id', '=', 'semesters.tahun_akademik_id')->where('semesters.is_aktif', true)->pluck('tahun_akademiks.nama', 'semesters.id');
                    })
                    ->default(function () {
                        $semester = Semester::where('is_aktif', true)->first();
                        if ($semester) {
                            return $semester->id;
                        }
                    })
                    ->disabled()
                    ->dehydrated(),
                Select::make('kelas_id')
                    ->label('Pilih Kelas')
                    ->options(function () {
                        return Kelas::all()->pluck('nama', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false),
                Select::make('santri_id')
                    ->label('Santri')
                    ->required()
                    ->options(function (callable $get) {
                        $kelasId = $get('kelas_id');
                        if ($kelasId) {
                            return Santri::where('kelas_id', $kelasId)->pluck('nama_lengkap', 'id');
                        }
                        return Santri::all()->pluck('nama_lengkap', 'id');
                    })
                    ->searchable()
                    ->preload(),
                Select::make('mapel_id')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->options(function () {
                        return Mapel::distinct()
                            ->get()
                            ->pluck('nama', 'id');
                    })
                    ->preload()
                    ->searchable(),
                TextInput::make('nilai')
                    ->label('Nilai Angka')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?int $state) {
                        if ($state >= 90 && $state <= 100) {
                            $set('predikat', 'A');
                        } elseif ($state >= 80 && $state <= 89) {
                            $set('predikat', 'B');
                        } elseif ($state >= 70 && $state <= 79) {
                            $set('predikat', 'C');
                        } elseif ($state >= 60 && $state <= 69) {
                            $set('predikat', 'D');
                        } elseif ($state > 0 && $state <= 58) {
                            $set('predikat', 'E');
                        } else {
                            $set('predikat', '-');
                        }
                    }),
                TextInput::make('nilai_huruf')
                    ->label('Nilai Huruf')
                    ->required(),
                TextInput::make('predikat')
                    ->label('Predikat')
                    ->required()
                    ->hidden()
                    ->dehydratedWhenHidden(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('semester.tahunAkademik.nama')
                    ->label('Tahun Akademik'),
                TextColumn::make('semester.tipe')
                    ->label('Semester'),
                TextColumn::make('santri.nama_lengkap')
                    ->label('Santri')
                    ->searchable(),
                TextColumn::make('santri.kelas.nama')
                    ->label('Kelas'),
                TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran'),
                TextColumn::make('nilai')
                    ->label('Nilai Angka'),
                TextColumn::make('predikat')
                    ->label('Predikat'),
            ])
            ->filters([
                SelectFilter::make('semester_id')
                    ->label('Semester')
                    ->relationship('semester', 'tipe'),
                SelectFilter::make('santri_id')
                    ->label('Santri')
                    ->relationship('santri', 'nama_lengkap'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat')
                        ->modalHeading('Detail Nilai')
                        ->modalCancelActionLabel('Tutup'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->modalHeading('Edit Data Nilai')
                        ->modalSubmitActionLabel('Perbarui')
                        ->modalCancelActionLabel('Batal'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Data Nilai')
                        ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                        ->modalCancelActionLabel('Batal')
                        ->modalSubmitActionLabel('Hapus'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNilais::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin' || $user->role == 'guru';
    }
}
