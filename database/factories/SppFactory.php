<?php

namespace Database\Factories;

use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Spp>
 */
class SppFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggal = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'semester_id' => Semester::factory(),
            'santri_id' => Santri::factory(),
            'tanggal' => $tanggal,
            'nominal' => 150_000,
            'bulan' => (int) $tanggal->format('n'),
            'tahun' => (int) $tanggal->format('Y'),
            'is_paid' => true,
            'paid_at' => $tanggal,
        ];
    }
}
