<?php

use App\Livewire\Admin\Lembagas;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\WhatsappBroadcast;
use App\Livewire\Akademik\JadwalPelajaran;
use App\Livewire\Akademik\Kelas;
use App\Livewire\Akademik\Mapel;
use App\Livewire\Akademik\TahunAkademik;
use App\Livewire\Kepegawaian\AbsensiGuru;
use App\Livewire\Kepegawaian\GajiGuru;
use App\Livewire\Kepegawaian\Guru;
use App\Livewire\Kesantrian\AbsensiSantri;
use App\Livewire\Kesantrian\Nilai;
use App\Livewire\Kesantrian\Santri;
use App\Livewire\Keuangan\Spp;
use App\Livewire\Konten\Contacts;
use App\Livewire\Konten\Galleries;
use App\Livewire\Konten\Subscriptions;
use App\Livewire\Settings\Index;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('akademik/tahun-akademik', TahunAkademik::class)->name('akademik.tahun-akademik');
    Route::livewire('akademik/kelas', Kelas::class)->name('akademik.kelas');
    Route::livewire('akademik/mapel', Mapel::class)->name('akademik.mapel');
    Route::livewire('akademik/jadwal-pelajaran', JadwalPelajaran::class)->name('akademik.jadwal-pelajaran');

    Route::livewire('kepegawaian/guru', Guru::class)->name('kepegawaian.guru');
    Route::livewire('kepegawaian/absensi', AbsensiGuru::class)->name('kepegawaian.absensi');
    Route::livewire('kepegawaian/gaji', GajiGuru::class)->name('kepegawaian.gaji');

    Route::livewire('kesantrian/santri', Santri::class)->name('kesantrian.santri');
    Route::livewire('kesantrian/absensi', AbsensiSantri::class)->name('kesantrian.absensi');
    Route::livewire('kesantrian/nilai', Nilai::class)->name('kesantrian.nilai');

    Route::livewire('keuangan/spp', Spp::class)->name('keuangan.spp');

    Route::livewire('konten/galeri', Galleries::class)->name('konten.galeri');
    Route::livewire('konten/pesan', Contacts::class)->name('konten.pesan');
    Route::livewire('konten/langganan', Subscriptions::class)->name('konten.langganan');

    Route::livewire('admin/lembagas', Lembagas::class)->name('admin.lembagas');
    Route::livewire('admin/users', Users::class)->name('admin.users');
    Route::livewire('admin/settings', Index::class)->name('admin.settings');
    Route::livewire('admin/whatsapp', WhatsappBroadcast::class)->name('admin.whatsapp');
});
