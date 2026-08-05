<?php

namespace Database\Factories;

use App\Models\Mapel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mapel>
 */
class MapelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->randomElement(['Fiqih', 'Akidah', 'Akhlak', 'Nahwu', 'Shorof', 'Tajwid', 'Hadits', 'Tafsir']),
            'kitab' => fake()->optional()->sentence(3),
            'kkm' => 60,
        ];
    }
}
