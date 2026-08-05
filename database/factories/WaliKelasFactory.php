<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaliKelas>
 */
class WaliKelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'guru_id' => Guru::factory(),
        ];
    }
}
