<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiResource\Pages;
use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\Semester;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
                Select::make('santri_id')
                    ->label('Santri')
                    ->required()
                    ->relationship('santri', 'nama_lengkap')
                    ->searchable()
                    ->preload(),
                Select::make('jadwal_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->options(function () {
                        return JadwalPelajaran::all()->pluck('mapel.nama', 'id');
                    })
                    ->preload()
                    ->searchable(),
                TextInput::make('nilai')
                    ->label('Nilai Angka')
                    ->required()
                    ->numeric(),
                TextInput::make('nilai_huruf')
                    ->label('Nilai Huruf')
                    ->required(),
                TextInput::make('predikat')
                    ->label('Predikat')
                    ->default(function ($record) {
                        $nilai = $record->nilai;
                        if ($nilai >= 99) {
                            return 'A+';
                        } elseif ($nilai >= 82 && $nilai <= 98) {
                            return 'A';
                        } elseif ($nilai >= 75 && $nilai <= 81) {
                            return 'B';
                        } elseif ($nilai >= 68 && $nilai <= 74) {
                            return 'C';
                        } else {
                            return 'D';
                        }
                    })
                    ->required()
                    ->disabled()
                    ->dehydrated(),
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
                    ->label('Santri'),
                TextColumn::make('jadwalPelajaran.mapel.nama')
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
