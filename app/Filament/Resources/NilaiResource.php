<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiResource\Pages;
use App\Models\Nilai;
use App\Models\TahunAkademik;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                Select::make('tahun_akademik_id')
                    ->label('Tahun Akademik')
                    ->required()
                    ->relationship('tahunAkademik', 'nama')
                    ->default(function () {
                        $tahunAkademik = TahunAkademik::where('is_aktif', true)->first();
                        if ($tahunAkademik) {
                            return $tahunAkademik->id;
                        }
                    })
                    ->disabled()
                    ->dehydrated(),
                Select::make('jadwal_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->relationship('jadwalPelajaran.mapel', 'nama')
                    ->searchable(),
                Select::make('santri_id')
                    ->label('Santri')
                    ->required()
                    ->relationship('santri', 'nama_lengkap')
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
                    ->required(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
