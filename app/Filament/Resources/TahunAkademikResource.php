<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAkademikResource\Pages;
use App\Filament\Resources\TahunAkademikResource\RelationManagers\SemesterRelationManager;
use App\Models\TahunAkademik;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\Table\RelationManagerAction;
use Illuminate\Support\Facades\Auth;

class TahunAkademikResource extends Resource
{
    protected static ?string $model = TahunAkademik::class;

    protected static ?string $navigationIcon = 'humble-calendar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Tahun Akademik';

    public static ?int $navigationGroupSort = 4;

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Tahun Akademik')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('semester.tipe'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_aktif')
                    ->label('Aktif')
                    ->options([
                        true => 'Aktif',
                        false => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                RelationManagerAction::make('semester-relation-manager')
                    ->label('Semester')
                    ->color('success')
                    ->icon('phosphor-eye')
                    ->modalHeading('Data Semester Tahun Akademik')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->relationManager(SemesterRelationManager::make()),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Tahun Akademik')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Tahun Akademik')
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
            'index' => Pages\ManageTahunAkademiks::route('/'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin';
    }
}
