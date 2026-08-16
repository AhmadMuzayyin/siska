<?php

namespace Database\Factories;

use App\Models\KategoriNilaiHarian;
use App\Models\Lembaga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriNilaiHarian>
 */
class KategoriNilaiHarianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lembaga_id' => Lembaga::factory(),
            'nama' => fake()->randomElement(['Sikap & Akhlaq', 'Kesopanan', 'Kedisiplinan', 'Capaian Hafalan', 'Adab Berguru']),
            'bobot' => fake()->numberBetween(10, 30),
            'is_wajib' => true,
            'keterangan' => fake()->sentence(),
        ];
    }
}
