<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaliKelasResource\Pages;
use App\Models\Guru;
use App\Models\WaliKelas;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WaliKelasResource extends Resource
{
    protected static ?string $model = WaliKelas::class;

    protected static ?string $navigationIcon = 'phosphor-chalkboard-teacher-light';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Wali Kelas';

    public static ?int $navigationGroupSort = 1;

    public static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama')
                    ->disableOptionWhen(function ($value) {
                        return WaliKelas::where('kelas_id', $value)->exists();
                    }),
                Select::make('guru_id')
                    ->label('Guru')
                    ->options(Guru::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama'),
                TextColumn::make('guru.user.name')
                    ->label('Wali Kelas'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Wali Kelas')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Wali Kelas')
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
            'index' => Pages\ManageWaliKelas::route('/'),
        ];
    }
}
