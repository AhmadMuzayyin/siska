<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lembaga' => fake()->company(),
            'nsm' => fake()->numerify('####################'),
            'alamat' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'telepon' => fake()->numerify('08##########'),
            'payroll_cutoff_day' => 25,
            'fitur_pesan_whatsapp' => false,
        ];
    }
}
