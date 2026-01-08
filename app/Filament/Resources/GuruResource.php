<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers\UserRelationManager;
use App\Models\Guru;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'phosphor-student';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Data Guru';

    public static ?int $navigationGroupSort = 1;

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Nama Guru')
                    ->relationship('user', 'name')
                    ->disabled(function ($state, $record) {
                        if ($record) {
                            $user = User::find($record->user_id);
                            if ($user && $user->role === 'admin') {
                                return true;
                            }
                        }

                        return false;
                    })
                    ->disableOptionWhen(function ($value, $label) {
                        $user = User::find($value);

                        return $user && $user->role === 'admin';
                    })
                    ->disabledOn(['edit'])
                    ->dehydrated(),
                TextInput::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->label('Alamat Lengkap'),
                TextInput::make('whatsapp')
                    ->required()
                    ->minLength(12)
                    ->maxLength(15)
                    ->label('No. Whatsapp Aktif'),
                Select::make('gender')->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])->required()
                    ->label('Jenis Kelamin'),
                Select::make('status')->options([
                    'Aktif' => 'Aktif',
                    'Tidak Aktif' => 'Tidak Aktif',
                ])->required()
                    ->label('Status Guru'),
                FileUpload::make('foto')
                    ->image()
                    ->directory('guru')
                    ->rules([
                        'dimensions' => 'dimensions:width=400,height=400',
                    ])
                    ->validationMessages([
                        'dimensions' => 'Foto harus berukuran 400x400',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat Lengkap')
                    ->limit(30),
                TextColumn::make('rfid_uid')
                    ->label('Kartu RFID')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Terdaftar' : 'Belum'),
                ImageColumn::make('foto')
                    ->label('Foto Guru'),
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
                SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ]),
                SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Guru')
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Guru')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Guru')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus'),
                Tables\Actions\Action::make('daftarkanKartu')
                    ->label('Daftarkan Kartu RFID')
                    ->icon('heroicon-o-rss')
                    ->color(fn (Guru $record) => $record->rfid_uid ? 'success' : 'warning')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Guru $record) => $record->rfid_uid ? 'Ganti Kartu RFID' : 'Daftarkan Kartu RFID')
                    ->modalDescription(fn (Guru $record) => $record->rfid_uid
                        ? 'Guru ini sudah memiliki kartu RFID. Scan kartu baru untuk mengganti.'
                        : 'Silahkan scan kartu RFID ke perangkat dalam waktu 2 menit.')
                    ->modalSubmitActionLabel('Siap Scan')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Guru $record) {
                        cache()->put('register_rfid_guru_id', $record->id, now()->addMinutes(2));
                        \Filament\Notifications\Notification::make()
                            ->title('Mode Pendaftaran Aktif')
                            ->body('Silahkan scan kartu RFID untuk guru ' . $record->user->name)
                            ->info()
                            ->send();
                    }),
                Tables\Actions\Action::make('hapusKartu')
                    ->label('Hapus Kartu RFID')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Kartu RFID')
                    ->modalDescription('Apakah anda yakin ingin menghapus kartu RFID dari guru ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->visible(fn (Guru $record) => $record->rfid_uid !== null)
                    ->action(function (Guru $record) {
                        $record->update(['rfid_uid' => null]);
                        \Filament\Notifications\Notification::make()
                            ->title('Kartu RFID Dihapus')
                            ->body('Kartu RFID guru ' . $record->user->name . ' berhasil dihapus.')
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
            UserRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin';
    }
}
