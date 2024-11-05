<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SppResource\Pages;
use App\Filament\Resources\SppResource\RelationManagers;
use App\Models\Spp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SppResource extends Resource
{
    protected static ?string $model = Spp::class;

    protected static ?string $navigationIcon = 'fluentui-receipt-money-20-o';
    protected static ?string $navigationGroup = 'Keuangan';
    public static ?int $navigationGroupSort = 3;
    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageSpps::route('/'),
        ];
    }
}
