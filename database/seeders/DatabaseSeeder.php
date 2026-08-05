<?php

namespace Database\Seeders;

use App\Enums\HariSekolah;
use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Models\WaliKelas;
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
            'lembaga' => 'TPQ & Madin Al-Hikmah',
            'nsm' => '121235000001',
            'alamat' => 'Jl. Pendidikan No. 123, Kecamatan Kota, Kabupaten Nusantara',
            'email' => 'info@alhikmah.test',
            'telepon' => '081234567890',
            'payroll_cutoff_day' => 25,
            'fitur_pesan_whatsapp' => true,
            'pesan_whatsapp' => 'Assalamu\'alaikum, ada yang bisa kami bantu mengenai TPQ & Madin Al-Hikmah?',
            'google_maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.0!2d112.7!3d-7.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTUnMDAuMCJTIDExMsKwNDInMDAuMCJF!5e0!3m2!1sen!2sid!4v1700000000000',
        ]);

        User::factory()->create([
            'name' => 'Admin Siska',
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

        $mapels = Mapel::factory()->count(4)->create();

        $kelasList = Kelas::factory()->count(3)->create();

        $kelasList->each(function (Kelas $kelas) use ($semesterGenap, $mapels) {
            $guru = Guru::factory()->create();

            WaliKelas::factory()->create([
                'kelas_id' => $kelas->id,
                'guru_id' => $guru->id,
            ]);

            Santri::factory()->count(10)->create([
                'kelas_id' => $kelas->id,
            ]);

            foreach ($mapels as $index => $mapel) {
                JadwalPelajaran::factory()->create([
                    'semester_id' => $semesterGenap->id,
                    'kelas_id' => $kelas->id,
                    'mapel_id' => $mapel->id,
                    'guru_id' => $guru->id,
                    'hari' => HariSekolah::cases()[$index % count(HariSekolah::cases())],
                ]);
            }
        });
    }
}
