<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Models\Kelas;
use App\Models\Santri;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
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

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'phosphor-users';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Data Santri';

    public static ?int $navigationGroupSort = 1;

    public static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Santri')
                    ->columns(2)
                    ->schema([
                        TextInput::make('noinduk')
                            ->required()
                            ->maxLength(255)
                            ->numeric()
                            ->label('No. Induk')
                            ->minValue(1),
                        Select::make('kelas_id')
                            ->required()
                            ->relationship('kelas', 'nama')
                            ->label('Kelas'),
                        TextInput::make('nama_lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nama_panggilan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tempat_lahir')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('tanggal_lahir')
                            ->required(),
                        TextInput::make('anak_ke')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Select::make('jenis_kelamin')
                            ->required()
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]),
                        Textarea::make('alamat')
                            ->required()
                            ->maxLength(255)
                            ->rows(3),
                    ]),
                Section::make('Data Orang Tua')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Ayah'),
                        TextInput::make('pendidikan_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Pendidikan Ayah'),
                        TextInput::make('pekerjaan_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Pekerjaan Ayah'),
                        TextInput::make('nama_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Ibu'),
                        TextInput::make('pendidikan_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Pendidikan Ibu'),
                        TextInput::make('pekerjaan_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Pekerjaan Ibu'),
                        TextInput::make('telepon_wali')
                            ->required()
                            ->maxLength(255)
                            ->label('Telepon Wali (Bapak/Ibu)')
                            ->hint('Masukkan nomor telepon wali dengan format 628xxxxxxx')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('noinduk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->searchable(),
                TextColumn::make('nama_panggilan')
                    ->searchable(),
                TextColumn::make('kelas.nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rfid_uid')
                    ->label('Kartu RFID')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Terdaftar' : 'Belum'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Dibuat'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Diubah'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->options(function () {
                        if (Auth::user()->role === 'guru') {
                            return Kelas::whereHas('waliKelas', function ($queryWaliKelas) {
                                $queryWaliKelas->where('guru_id', Auth::user()->guru->id);
                            })->get()->pluck('nama', 'id');
                        } else {
                            return Kelas::all()->pluck('nama', 'id');
                        }
                    })
                    ->label('Kelas'),
                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Santri')
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Santri')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal')
                    ->hidden(fn($record) => Auth::user()->role == 'guru'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Santri')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus')
                    ->hidden(fn($record) => Auth::user()->role == 'guru'),
                Tables\Actions\Action::make('daftarkanKartu')
                    ->label('Daftarkan Kartu RFID')
                    ->icon('heroicon-o-rss')
                    ->color(fn (Santri $record) => $record->rfid_uid ? 'success' : 'warning')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Santri $record) => $record->rfid_uid ? 'Ganti Kartu RFID' : 'Daftarkan Kartu RFID')
                    ->modalDescription(fn (Santri $record) => $record->rfid_uid
                        ? 'Santri ini sudah memiliki kartu RFID. Scan kartu baru untuk mengganti.'
                        : 'Silahkan scan kartu RFID ke perangkat dalam waktu 2 menit.')
                    ->modalSubmitActionLabel('Siap Scan')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Santri $record) {
                        cache()->put('register_rfid_santri_id', $record->id, now()->addMinutes(2));
                        \Filament\Notifications\Notification::make()
                            ->title('Mode Pendaftaran Aktif')
                            ->body('Silahkan scan kartu RFID untuk santri ' . $record->nama_lengkap)
                            ->info()
                            ->send();
                    }),
                Tables\Actions\Action::make('hapusKartu')
                    ->label('Hapus Kartu RFID')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Kartu RFID')
                    ->modalDescription('Apakah anda yakin ingin menghapus kartu RFID dari santri ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->visible(fn (Santri $record) => $record->rfid_uid !== null)
                    ->action(function (Santri $record) {
                        $record->update(['rfid_uid' => null]);
                        \Filament\Notifications\Notification::make()
                            ->title('Kartu RFID Dihapus')
                            ->body('Kartu RFID santri ' . $record->nama_lengkap . ' berhasil dihapus.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin' || $user->role == 'guru';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery(); // Ambil query default
        $user = Auth::user();
        if ($user->role === 'guru') {
            $guruId = $user->guru->id;
            $query->with('kelas')->whereHas('kelas', function ($queryKelas) use ($guruId) {
                $queryKelas->whereHas('waliKelas', function ($queryWaliKelas) use ($guruId) {
                    $queryWaliKelas->where('guru_id', $guruId);
                });
            });
            return $query;
        } else {
            return $query;
        }
    }
}
