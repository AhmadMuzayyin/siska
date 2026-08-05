<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Santri>
 */
class SantriFactory extends Factory
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
            'noinduk' => fake()->unique()->numerify('##########'),
            'rfid_uid' => null,
            'nama_lengkap' => fake()->name(),
            'nama_panggilan' => fake()->firstName(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-15 years', '-6 years'),
            'anak_ke' => fake()->numberBetween(1, 6),
            'alamat' => fake()->address(),
            'jenis_kelamin' => fake()->randomElement(Gender::cases()),
            'nama_ayah' => fake()->name('male'),
            'pendidikan_ayah' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2']),
            'pekerjaan_ayah' => fake()->jobTitle(),
            'nama_ibu' => fake()->name('female'),
            'pendidikan_ibu' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2']),
            'pekerjaan_ibu' => fake()->jobTitle(),
            'telepon_wali' => '08'.fake()->numberBetween(1, 9).fake()->numerify('#########'),
            'status' => SantriStatus::Aktif,
        ];
    }

    /**
     * Indicate that the santri registered publicly and is awaiting admin approval.
     */
    public function pendingApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SantriStatus::PendingApproval,
        ]);
    }
}
