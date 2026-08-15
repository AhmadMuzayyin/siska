<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Setting::query()->firstOrCreate([], [
            'is_installed' => true,
            'is_multi_lembaga' => true,
            'installed_modules' => ['akademik', 'jadwal_absensi', 'nilai', 'spp', 'absensi_guru', 'gaji_guru', 'konten', 'lembagas'],
            'lembaga' => 'MDTA ARROQY',
            'nsm' => '121235000001',
            'alamat' => 'Jl. Bukit Ganji, Desa Gadu Barat, Kec. Ganding, Kab. Sumenep',
            'email' => 'arroqy@gmail.com',
            'telepon' => '6285179695497',
            'payroll_cutoff_day' => 25,
            'fitur_pesan_whatsapp' => true,
            'pesan_whatsapp' => 'Assalamu\'alaikum, ada yang bisa kami bantu mengenai MDTA ARROQY?',
            'google_maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.922616837216!2d113.6832844757407!3d-7.018382168753321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd9dc6227720e73%3A0xa475564987ce7704!2sARROQY!5e0!3m2!1sid!2sid!4v1786813851495!5m2!1sid!2sid',
        ]);

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'role' => UserRole::Admin,
        ]);

        $tahunAkademik = TahunAkademik::factory()->create();

        $semesterGanjil = Semester::factory()->for($tahunAkademik)->create([
            'tipe' => 'ganjil',
        ]);

        $semesterGenap = Semester::factory()->for($tahunAkademik)->active()->create([
            'tipe' => 'genap',
        ]);
    }
}
