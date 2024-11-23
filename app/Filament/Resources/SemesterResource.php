<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterResource\Pages;
use App\Models\Semester;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'humble-calendar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Semester';

    public static ?int $navigationGroupSort = 4;

    public static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tahun_akademik_id')
                    ->required()
                    ->relationship('tahunAkademik', 'nama')
                    ->label('Tahun Akademik'),
                Select::make('tipe')
                    ->required()
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->label('Tipe Semester'),
                DatePicker::make('mulai')
                    ->required()
                    ->label('Mulai'),
                DatePicker::make('selesai')
                    ->required()
                    ->label('Selesai'),
                Toggle::make('is_aktif')
                    ->required()
                    ->label('Status')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahunAkademik.nama')
                    ->sortable()
                    ->disabledClick(),
                TextColumn::make('tipe')
                    ->disabledClick(),
                TextColumn::make('mulai')
                    ->date()
                    ->sortable()
                    ->disabledClick(),
                TextColumn::make('selesai')
                    ->date()
                    ->sortable()
                    ->disabledClick(),
                IconColumn::make('is_aktif')
                    ->boolean()
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->action(function (Semester $record) {
                        $record->tahunAkademik->semester()
                            ->where('id', '!=', $record->id)
                            ->where('is_aktif', true)
                            ->update(['is_aktif' => false]);
                        $record->is_aktif = ! $record->is_aktif;
                        $record->save();
                        Notification::make()
                            ->title('Status berhasil diperbarui')
                            ->success()
                            ->send();
                    }),
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
                //
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
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
