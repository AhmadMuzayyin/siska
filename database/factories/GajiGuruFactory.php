<?php

namespace Database\Factories;

use App\Models\GajiGuru;
use App\Models\Guru;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GajiGuru>
 */
class GajiGuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bisyaroh = 2_600_000;
        $jumlahHadir = fake()->numberBetween(15, 26);

        return [
            'semester_id' => Semester::factory(),
            'guru_id' => Guru::factory(),
            'bisyaroh' => $bisyaroh,
            'jumlah_hadir' => $jumlahHadir,
            'total_gaji' => (int) round($bisyaroh / 26 * $jumlahHadir),
            'bulan' => fake()->numberBetween(1, 12),
            'tahun' => fake()->numberBetween(2023, 2026),
        ];
    }
}
