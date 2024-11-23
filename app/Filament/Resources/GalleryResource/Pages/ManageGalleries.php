<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGalleries extends ManageRecords
{
    protected static string $resource = GalleryResource::class;

    protected static ?string $title = 'Galeri';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Galeri')
                ->icon('heroicon-o-plus')
                ->color('success')
                // ->action('create')
                ->modalHeading('Tambah Galeri')
                ->modalDescription('Tambahkan galeri baru untuk kegiatan, wisata, atau bimbingan')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
