<?php

namespace Database\Factories;

use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nilai>
 */
class NilaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'semester_id' => Semester::factory(),
            'santri_id' => Santri::factory(),
            'mapel_id' => Mapel::factory(),
            'nilai' => fake()->numberBetween(0, 100),
            'keterangan' => fake()->optional()->sentence(),
        ];
    }
}
