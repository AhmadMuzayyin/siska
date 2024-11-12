<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use App\Models\Kelas;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'fluentui-data-usage-edit-20-o';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Absensi';

    public static ?int $navigationGroupSort = 2;

    public static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                View::make('Absensi.create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahunAkademik.nama')
                    ->sortable()
                    ->label('Tahun Akademik'),
                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->sortable()
                    ->label('Santri'),
                Tables\Columns\TextColumn::make('jadwalPelajaran.kelas.nama')
                    ->sortable()
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('jadwalPelajaran.mapel.nama')
                    ->sortable()
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('jadwalPelajaran.kelas', 'nama'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Absensi')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user->role == 'admin' || $user->role == 'guru';
    }
}
