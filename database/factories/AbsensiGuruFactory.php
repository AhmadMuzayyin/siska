<?php

namespace Database\Factories;

use App\Enums\TeacherAttendanceStatus;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AbsensiGuru>
 */
class AbsensiGuruFactory extends Factory
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
            'guru_id' => Guru::factory(),
            'status' => TeacherAttendanceStatus::Hadir,
            'tanggal' => fake()->date(),
        ];
    }
}
