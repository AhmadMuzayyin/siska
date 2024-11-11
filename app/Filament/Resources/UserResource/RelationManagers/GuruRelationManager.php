<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuruRelationManager extends RelationManager
{
    protected static string $relationship = 'guru';

    protected static ?string $title = 'Data Guru';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->label('Alamat Lengkap'),
                TextInput::make('whatsapp')
                    ->required()
                    ->minLength(12)
                    ->maxLength(15)
                    ->label('No. Whatsapp Aktif'),
                Select::make('jenis')->options([
                    'Guru' => 'Guru',
                    'Kepala Sekolah' => 'Kepala Sekolah',
                ])->required()
                    ->label('Jabatan Guru'),
                Select::make('gender')->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])->required()
                    ->label('Jenis Kelamin'),
                Select::make('status')->options([
                    'Aktif' => 'Aktif',
                    'Tidak Aktif' => 'Tidak Aktif',
                ])->required()
                    ->label('Status Aktif'),
                FileUpload::make('foto')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alamat')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Lengkap'),
                TextColumn::make('alamat')
                    ->label('Alamat Lengkap'),
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Data Guru')
                    ->icon('phosphor-plus')
                    ->color('success'),
            ])
            ->actions([
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
