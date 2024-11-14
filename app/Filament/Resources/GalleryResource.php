<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Filament\Resources\GalleryResource\RelationManagers;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Utilities';

    protected static ?string $navigationLabel = 'Galeri';

    public static ?int $navigationGroupSort = 1;

    public static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->options([
                        'kegiatan' => 'Kegiatan',
                        'wisata' => 'Wisata',
                        'bimbingan' => 'Bimbingan',
                    ])
                    ->required()
                    ->label('Kategori'),
                TextInput::make('title')
                    ->required()
                    ->label('Judul'),
                FileUpload::make('image')
                    ->required()
                    ->image()
                    ->label('Gambar')
                    ->directory('galleries')
                    ->rules([
                        'image',
                        'max:1024',
                        'dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
                    ])
                    ->validationMessages([
                        'image' => 'Gambar harus berupa file gambar',
                        'max' => 'Gambar tidak boleh lebih dari 1MB',
                        'dimensions' => 'Gambar harus berukuran antara 100x100 dan 1000x1000 piksel',
                    ]),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('#'),
                TextColumn::make('type')
                    ->label('Kategori'),
                TextColumn::make('title')
                    ->label('Judul'),
                ImageColumn::make('image')
                    ->label('Gambar'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'kegiatan' => 'Kegiatan',
                        'wisata' => 'Wisata',
                        'bimbingan' => 'Bimbingan',
                    ])
                    ->label('Kategori'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Galeri')
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Galeri')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Galeri')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus')
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
            'index' => Pages\ManageGalleries::route('/'),
        ];
    }
}
