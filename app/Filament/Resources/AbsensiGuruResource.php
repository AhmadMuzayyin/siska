<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiGuruResource\Pages;
use App\Models\AbsensiGuru;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AbsensiGuruResource extends Resource
{
    protected static ?string $model = AbsensiGuru::class;

    protected static ?string $navigationIcon = 'fluentui-data-usage-edit-20-o';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Absensi Guru';

    public static ?int $navigationGroupSort = 2;

    public static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('guru_id')
                    ->label('Guru')
                    ->options(function () {
                        return \App\Models\Guru::all()->pluck('user.name', 'id');
                    })
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('bisyaroh')
                    ->label('Bisyaroh')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Select::make('status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin' => 'Izin',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('guru.user.name')
                    ->label('Guru')
                    ->sortable(),
                TextColumn::make('bisyaroh')
                    ->label('Bisyaroh')
                    ->sortable(),
                TextColumn::make('status')
                    ->sortable(),
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
            'index' => Pages\ManageAbsensiGurus::route('/'),
        ];
    }
}
