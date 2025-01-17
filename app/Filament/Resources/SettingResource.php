<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'humble-cog';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Pengaturan';

    public static ?int $navigationGroupSort = 4;

    public static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('lembaga')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lembaga'),
                TextInput::make('nsm')
                    ->required()
                    ->maxLength(255)
                    ->label('NSM')
                    ->numeric(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email'),
                TextInput::make('telepon')
                    ->required()
                    ->maxLength(255)
                    ->label('Telepon')
                    ->tel(),
                FileUpload::make('logo')
                    ->required()
                    ->image()
                    ->label('Logo')
                    ->directory('settings')
                    ->maxSize(10240)
                    ->imageEditor(),
                FileUpload::make('favicon')
                    ->required()
                    ->image()
                    ->label('Favicon')
                    ->directory('settings')
                    ->maxSize(10240)
                    ->imageEditor(),
                Textarea::make('alamat')
                    ->required()
                    ->rows(3)
                    ->label('Alamat')
                    ->columnSpanFull(),
                TextInput::make('meta_deskripsi')
                    ->label('Meta Deskripsi'),
                TextInput::make('meta_keyword')
                    ->label('Meta Keyword'),
                TextInput::make('api_key_whatsapp')
                    ->label('API Key Whatsapp')
                    ->columnSpanFull(),
                RichEditor::make('pesan_whatsapp')
                    ->label('Pesan Whatsapp')
                    ->hint('Variable data yang dapat digunakan: {nama}, {noinduk}, {kelas}, {alamat}, {ayah}, {ibu}')
                    ->hintColor('danger')
                    ->hintIcon('phosphor-info')
                    ->columnSpanFull(),
                Toggle::make('fitur_pesan_whatsapp')
                    ->label('Fitur Pesan Whatsapp')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lembaga')
                    ->label('Nama Lembaga'),
                TextColumn::make('nsm')
                    ->label('NSM'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('telepon')
                    ->label('Telepon'),
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
                    ->modalHeading('Edit Data Pengaturan')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Pengaturan')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin';
    }
}
