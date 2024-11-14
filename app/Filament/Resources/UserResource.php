<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\GuruRelationManager;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Pengguna';

    public static ?int $navigationGroupSort = 4;

    public static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->required()
                    ->confirmed()
                    ->password()
                    ->maxLength(255)
                    ->helperText('Kata sandi default adalah "password"')
                    ->default('password')
                    ->hidden(function ($record) {
                        if ($record && $record->account_type == 'google') {
                            return true;
                        }
                        return false;
                    }),
                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Kata Sandi')
                    ->required()
                    ->password()
                    ->maxLength(255)
                    ->helperText('Kata sandi default adalah "password"')
                    ->default('password')
                    ->hidden(function ($record) {
                        if ($record && $record->account_type == 'google') {
                            return true;
                        }
                        return false;
                    }),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'keuangan' => 'Keuangan',
                        'guru' => 'Guru',
                    ])->columnSpanFull(),
                Toggle::make('is_verified')
                    ->label('Status Pengguna')
                    ->inline()
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(false)
                    ->disabled(function ($record) {
                        if ($record && $record->email == 'admin@mq-alamin.com') {
                            return true;
                        }
                        return false;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap'),
                TextColumn::make('email')
                    ->label('Email'),
                IconColumn::make('is_verified')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('account_type')
                    ->label('Tipe Akun')
                    ->badge()
                    ->color(fn($state) => $state == 'google' ? 'success' : 'info')
                    ->icon(fn($state) => $state == 'google' ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->formatStateUsing(fn($state) => $state == 'google' ? 'Google' : 'Email'),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah Pada')
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
                    ->hidden(fn($record) => $record && ($record->email == 'admin@mq-alamin.com' || $record->email == 'keuangan@mq-alamin.com')),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->hidden(fn($record) => $record && ($record->email == 'admin@mq-alamin.com' || $record->email == 'keuangan@mq-alamin.com'))
                    ->modalHeading('Hapus Data Pengguna')
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

    public static function getRelations(): array
    {
        return [
            GuruRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user->role == 'admin';
    }
}
