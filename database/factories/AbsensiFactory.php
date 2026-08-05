<?php

namespace Database\Factories;

use App\Enums\StudentAttendanceStatus;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Absensi>
 */
class AbsensiFactory extends Factory
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
            'jadwal_pelajaran_id' => JadwalPelajaran::factory(),
            'tanggal' => fake()->date(),
            'status' => StudentAttendanceStatus::Hadir,
        ];
    }
}
