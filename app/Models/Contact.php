<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'subject', 'message', 'nama', 'subjek', 'pesan', 'is_dibaca'])]
class Contact extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_dibaca' => 'boolean',
        ];
    }
}
