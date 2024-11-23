<?php

namespace App\Filament\Resources\TahunAkademikResource\RelationManagers;

use App\Models\Semester;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Concerns\CanBeEmbeddedInModals;

class SemesterRelationManager extends RelationManager
{
    use CanBeEmbeddedInModals;

    protected static string $relationship = 'semester';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipe')
                    ->required()
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->label('Tipe')
                    ->columnSpanFull(),
                DatePicker::make('mulai')
                    ->required()
                    ->label('Mulai'),
                DatePicker::make('selesai')
                    ->required()
                    ->label('Selesai'),
                Toggle::make('is_aktif')
                    ->required()
                    ->label('Status')
                    ->default(function ($record) {
                        return $record?->is_aktif;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tahun_akademik_id')
            ->columns([
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->label('Tahun Akademik'),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('mulai')
                    ->label('Mulai'),
                Tables\Columns\TextColumn::make('selesai')
                    ->label('Selesai'),
                Tables\Columns\IconColumn::make('is_aktif')
                    ->label('Status')
                    ->boolean()
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->action(function ($record) {
                        if (! $record->is_aktif) {
                            $record->tahunAkademik->semester()
                                ->where('id', '!=', $record->id)
                                ->where('is_aktif', true)
                                ->update(['is_aktif' => false]);
                        }
                        $record->is_aktif = ! $record->is_aktif;
                        $record->save();
                        Notification::make()
                            ->title('Status berhasil diperbarui')
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Semester')
                    ->modalHeading('Tambah Semester')
                    ->modalSubmitActionLabel('Tambah')
                    ->modalCancelActionLabel('Batal')
                    ->color('success')
                    ->icon('phosphor-plus')
                    ->createAnother(false)
                    ->hidden(fn($livewire) => $livewire->ownerRecord->semester()->count() >= 2),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Semester')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Semester')
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
