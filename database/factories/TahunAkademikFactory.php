<?php

namespace Database\Factories;

use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAkademik>
 */
class TahunAkademikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->unique()->numberBetween(2020, 2035);

        return [
            'nama' => "{$start}/".($start + 1),
        ];
    }
}
