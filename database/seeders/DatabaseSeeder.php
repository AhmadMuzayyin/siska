<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAkademik;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@mqalamin.sch.id',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);
        User::create([
            'name' => 'Keuangan',
            'email' => 'keuangan@mqalamin.sch.id',
            'role' => 'keuangan',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);
        $tahunAkademik = TahunAkademik::create([
            'nama' => '2024/2025',
            'semester' => 'Ganjil',
            'mulai' => '2024-01-01',
            'selesai' => '2025-12-31',
            'is_aktif' => true
        ]);
        $mapels = [
            'Fashohah' => 'فصاحة',
            'Tajwid' => 'تجويد',
            'Ghorib Musykilat' => 'غريب',
            'Suara & Lagu' => 'النغم',
            'Teori Ilmu Tajwid' => 'هداية الصبيان',
            'Tauhid' => 'عقيدة العوام',
            'Fiqih' => 'فقه',
            'Khottul Jamil' => 'خطل الجمل',
            'Juz 30' => 'الجز',
            'Juz 29' => 'الجز',
            'Juz 1' => 'الجز',
            'Rotibul Haddad' => 'راتبل الحداد',
            'Praktek Shalat' => 'فصلاتن',
        ];
        foreach ($mapels as $key => $value) {
            Mapel::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'nama' => $key,
                'kitab' => $value,
                'kkm' => 60
            ]);
        }
        $user_guru = User::create([
            'name' => 'Faiz',
            'email' => 'faiz@mqalamin.sch.id',
            'role' => 'guru',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);
        $guru = Guru::create([
            'user_id' => $user_guru->id,
            'alamat' => 'Jl. Raya',
            'whatsapp' => '6281234567890',
            'gender' => 'Laki-laki',
            'foto' => null,
            'status' => 'Aktif',
        ]);
        $kelas = [
            'Paud',
            'Jilid I',
            'Jilid II',
            'Jilid III',
            'Jilid IV',
            'Jilid V',
            'Jilid VI',
            'Shifir',
            'MDTA I',
            'MDTA II',
            'MDTA III',
            'MDTA IV',
        ];
        foreach ($kelas as $key => $value) {
            Kelas::create([
                'guru_id' => $guru->id,
                'nama' => $value,
                'kapasitas' => 10
            ]);
        }
    }
}
