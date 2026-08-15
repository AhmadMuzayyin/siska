<?php

namespace App\Enums;

enum GalleryType: string
{
    case Kegiatan = 'kegiatan';
    case Wisata = 'wisata';
    case Bimbingan = 'bimbingan';

    public function label(): string
    {
        return match ($this) {
            self::Kegiatan => __('Kegiatan'),
            self::Wisata => __('Wisata & Rihlah'),
            self::Bimbingan => __('Bimbingan & Halaqah'),
        };
    }
}
