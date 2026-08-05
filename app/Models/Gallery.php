<?php

namespace App\Models;

use App\Enums\GalleryType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'title', 'image', 'description', 'judul', 'deskripsi', 'tipe', 'path', 'urutan'])]
class Gallery extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => GalleryType::class,
            'urutan' => 'integer',
        ];
    }
}
