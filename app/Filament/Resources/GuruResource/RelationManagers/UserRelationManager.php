<?php

namespace App\Filament\Resources\GuruResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('role')
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn ($state) => $state == 'guru' ? 'success' : ($state == 'admin' ? 'warning' : 'danger'))
                    ->formatStateUsing(fn ($state) => $state == 'guru' ? 'Guru' : ($state == 'admin' ? 'Admin' : 'Kepala Sekolah')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->label('Tambah Pengguna')
                //     ->icon('phosphor-plus')
                //     ->color('success')
                //     ->hidden(fn($livewire) => $livewire->ownerRecord->user !== null),
            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                //     ->label('Edit')
                //     ->modalHeading('Edit Data Pengguna')
                //     ->modalSubmitActionLabel('Perbarui')
                //     ->modalCancelActionLabel('Batal'),
                // Tables\Actions\DeleteAction::make()
                //     ->label('Hapus')
                //     ->modalHeading('Hapus Data Pengguna')
                //     ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                //     ->modalCancelActionLabel('Batal')
                //     ->modalSubmitActionLabel('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
