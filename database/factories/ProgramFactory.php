<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lembaga_id' => null,
            'nama_program' => fake()->sentence(3),
            'kategori_badge' => fake()->randomElement(['METODE TILAWATI', 'PROGRAM UNGGULAN', 'AKADEMIK & DINIYAH']),
            'deskripsi_singkat' => fake()->paragraph(),
            'materi_unggulan' => [
                ['judul' => fake()->sentence(3), 'deskripsi' => fake()->sentence(6)],
                ['judul' => fake()->sentence(3), 'deskripsi' => fake()->sentence(6)],
            ],
            'gambar_url' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=800&q=80&auto=format&fit=crop',
            'icon' => 'book-open',
            'urutan' => 0,
            'is_active' => true,
        ];
    }
}
