<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'nama_program' => 'Taman Pendidikan Al-Qur\'an (TPQ)',
                'kategori_badge' => 'METODE TILAWATI',
                'deskripsi_singkat' => 'Program utama akselerasi baca Al-Qur\'an metode Tilawati dengan lagu rost yang mudah dan menyenangkan. Dimulai dari jilid 1 sampai Al-Qur\'an dan munaqosyah kelulusan bersanad.',
                'materi_unggulan' => [
                    ['judul' => 'Tilawati Jilid 1 s/d 6', 'deskripsi' => 'Pembelajaran tartil bertahap lagu rost'],
                    ['judul' => 'Tajwid & Ghorib Al-Qur\'an', 'deskripsi' => 'Pemahaman hukum bacaan mendalam'],
                    ['judul' => 'Munaqosyah & Ijazah Resmi', 'deskripsi' => 'Ujian kelulusan terstandarisasi Nurul Falah'],
                    ['judul' => 'Hafalan Surat Pendek Juz 30', 'deskripsi' => 'Setoran hafalan bertahap dan istiqomah'],
                ],
                'gambar_url' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=800&q=80&auto=format&fit=crop',
                'icon' => 'book-open',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'nama_program' => 'Madrasah Diniyah Takmiliyah (MDTA)',
                'kategori_badge' => 'AKADEMIK & DINIYAH',
                'deskripsi_singkat' => 'Pendidikan keagamaan Islam berjenjang formal meliputi Fiqih Ibadah, Aqidah Akhlak, Bahasa Arab, Tarikh Islam, dan Hadits untuk membekali pemahaman agama santri.',
                'materi_unggulan' => [
                    ['judul' => 'Fiqih Praktis & Safinatun Najah', 'deskripsi' => 'Kajian tata cara ibadah sehari-hari'],
                    ['judul' => 'Aqidatul Awwam & Tauhid', 'deskripsi' => 'Penanaman akidah ahlussunnah wal jamaah'],
                    ['judul' => 'Dasar Bahasa Arab & Nahwu', 'deskripsi' => 'Pengenalan kosakata dan tata bahasa dasar'],
                    ['judul' => 'Adab & Akhlaqul Banin/Banat', 'deskripsi' => 'Pembentukan karakter santri mulia'],
                ],
                'gambar_url' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=800&q=80&auto=format&fit=crop',
                'icon' => 'academic-cap',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'nama_program' => 'Tahfidzul Qur\'an & Khotmil',
                'kategori_badge' => 'PROGRAM UNGGULAN',
                'deskripsi_singkat' => 'Program bimbingan intensif hafalan Al-Qur\'an dengan metode talaqqi dan muroja\'ah terstruktur, didampingi pembina tahfidz bersertifikat mutqin.',
                'materi_unggulan' => [
                    ['judul' => 'Tahfidz Juz 30 & Pilihan', 'deskripsi' => 'Target hafalan Surat Yasin, Al-Waqiah, Al-Mulk'],
                    ['judul' => 'Setoran Harian & Muroja\'ah', 'deskripsi' => 'Sistem monitoring mutaba\'ah harian'],
                    ['judul' => 'Bimbingan Tajwid Mutqin', 'deskripsi' => 'Koreksi makhraj huruf dan sifat huruf'],
                    ['judul' => 'Wisuda Khotmil Qur\'an', 'deskripsi' => 'Apresiasi dan tasyakuran capaian santri'],
                ],
                'gambar_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=800&q=80&auto=format&fit=crop',
                'icon' => 'sparkles',
                'urutan' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $data) {
            Program::query()->updateOrCreate(
                ['nama_program' => $data['nama_program']],
                $data
            );
        }
    }
}
