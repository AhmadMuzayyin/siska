<?php

namespace App;

enum GalleryType: string
{
    case KEGITAN = 'kegiatan';
    case WISATA = 'wisata';
    case BIMBINGAN = 'bimbingan';

    public static function values(): array
    {
        return array_map(fn ($type) => $type->value, self::cases());
    }
}
