<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TahunAkademik;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TahunAkademikResource\Pages;
use App\Filament\Resources\TahunAkademikResource\RelationManagers;

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
                DatePicker::make('mulai')
                    ->label('Mulai')
                    ->required(),
                DatePicker::make('selesai')
                    ->label('Selesai')
                    ->required(),
                Toggle::make('is_aktif')
                    ->label('Aktif')
                    ->default(false),
                Toggle::make('is_locked')
                    ->label('Kunci')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('mulai')
                    ->date('d M Y'),
                TextColumn::make('selesai')
                    ->date('d M Y'),
                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean(),
                IconColumn::make('is_locked')
                    ->label('Kunci')
                    ->boolean(),
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
}
