<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPelajaranResource\Pages;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Semester;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Jadwal Pelajaran';

    public static ?int $navigationGroupSort = 2;

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('semester_id')
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
                    ->columnSpanFull()->disabled()->dehydrated(),
                Group::make([
                    Select::make('kelas_id')
                        ->required()
                        ->relationship('kelas', 'nama'),
                    Select::make('mapel_id')
                        ->required()
                        ->relationship('mapel', 'nama'),
                    Select::make('guru_id')
                        ->label('Guru')
                        ->required()
                        ->options(function () {
                            return Guru::all()->pluck('user.name', 'id');
                        }),
                ])->columns(3)->columnSpanFull(),
                TimePicker::make('jam_mulai')
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->required(),
                Select::make('hari')
                    ->required()
                    ->options([
                        'Minggu' => 'Minggu',
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('semester.tahunAkademik.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kelas.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mapel.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('guru.user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hari')
                    ->searchable(),
                TextColumn::make('jam_mulai'),
                TextColumn::make('jam_selesai'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Jadwal Pelajaran')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Jadwal Pelajaran')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus'),
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
            'index' => Pages\ManageJadwalPelajarans::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin';
    }
}
