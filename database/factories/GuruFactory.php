<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\GuruStatus;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'alamat' => fake()->address(),
            'whatsapp' => '08'.fake()->numberBetween(1, 9).fake()->numerify('#########'),
            'gender' => fake()->randomElement(Gender::cases()),
            'status' => GuruStatus::Aktif,
            'rfid_uid' => null,
        ];
    }

    /**
     * Indicate that the guru is no longer active.
     */
    public function tidakAktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => GuruStatus::TidakAktif,
        ]);
    }
}
