<?php

namespace Database\Factories;

use App\Enums\SemesterType;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'tahun_akademik_id' => TahunAkademik::factory(),
            'tipe' => fake()->randomElement(SemesterType::cases()),
            'mulai' => $mulai,
            'selesai' => (clone $mulai)->modify('+6 months'),
            'is_aktif' => false,
        ];
    }

    /**
     * Indicate that the semester is the active one.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_aktif' => true,
        ]);
    }
}
