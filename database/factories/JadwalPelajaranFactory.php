<?php

namespace Database\Factories;

use App\Enums\HariSekolah;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JadwalPelajaran>
 */
class JadwalPelajaranFactory extends Factory
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
            'kelas_id' => Kelas::factory(),
            'mapel_id' => Mapel::factory(),
            'guru_id' => Guru::factory(),
            'hari' => fake()->randomElement(HariSekolah::cases()),
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:30:00',
        ];
    }
}
