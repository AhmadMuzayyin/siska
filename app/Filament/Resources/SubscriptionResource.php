<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Utilities';

    protected static ?string $navigationLabel = 'Pengikut';

    public static ?int $navigationGroupSort = 3;

    public static ?int $navigationSort = 5;

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
                TextColumn::make('index')
                    ->label('#')
                    ->sortable()
                    ->rowIndex(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                DeleteAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->modalHeading('Hapus pengikut')
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
            'index' => Pages\ManageSubscriptions::route('/'),
        ];
    }
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->role == 'admin';
    }
}
