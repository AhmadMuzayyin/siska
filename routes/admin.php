<?php

use App\Http\Controllers\ExportImportController;
use App\Http\Controllers\RaporPrintController;
use App\Livewire\Admin\Lembagas;
use App\Livewire\Admin\Roles;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\WhatsappBroadcast;
use App\Livewire\Akademik\JadwalPelajaran;
use App\Livewire\Akademik\KalenderAkademik;
use App\Livewire\Akademik\KategoriNilaiHarian;
use App\Livewire\Akademik\Kelas;
use App\Livewire\Akademik\Mapel;
use App\Livewire\Akademik\TahunAkademik;
use App\Livewire\Kepegawaian\AbsensiGuru;
use App\Livewire\Kepegawaian\GajiGuru;
use App\Livewire\Kepegawaian\Guru;
use App\Livewire\Kesantrian\AbsensiSantri;
use App\Livewire\Kesantrian\Nilai;
use App\Livewire\Kesantrian\NilaiHarian;
use App\Livewire\Kesantrian\Santri;
use App\Livewire\Keuangan\HaflatulImtihan;
use App\Livewire\Keuangan\Spp;
use App\Livewire\Keuangan\Tabungan;
use App\Livewire\Konten\Contacts;
use App\Livewire\Konten\Galleries;
use App\Livewire\Konten\Subscriptions;
use App\Livewire\Settings\Index;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('akademik/tahun-akademik', TahunAkademik::class)->name('akademik.tahun-akademik');
    Route::livewire('akademik/kalender-akademik', KalenderAkademik::class)->name('akademik.kalender-akademik');
    Route::livewire('akademik/kelas', Kelas::class)->name('akademik.kelas');
    Route::livewire('akademik/mapel', Mapel::class)->name('akademik.mapel');
    Route::livewire('akademik/kategori-nilai-harian', KategoriNilaiHarian::class)->name('akademik.kategori-nilai-harian');
    Route::livewire('akademik/jadwal-pelajaran', JadwalPelajaran::class)->name('akademik.jadwal-pelajaran');
    Route::livewire('akademik/rapor', 'akademik.rapor')->name('akademik.rapor');
    Route::get('akademik/rapor/print/{santri}', [RaporPrintController::class, 'print'])->name('akademik.rapor.print');

    Route::livewire('kepegawaian/guru', Guru::class)->name('kepegawaian.guru');
    Route::livewire('kepegawaian/absensi', AbsensiGuru::class)->name('kepegawaian.absensi');
    Route::livewire('kepegawaian/gaji', GajiGuru::class)->name('kepegawaian.gaji');

    Route::livewire('kesantrian/santri', Santri::class)->name('kesantrian.santri');
    Route::livewire('kesantrian/absensi', AbsensiSantri::class)->name('kesantrian.absensi');
    Route::livewire('kesantrian/nilai', Nilai::class)->name('kesantrian.nilai');
    Route::livewire('kesantrian/nilai-harian', NilaiHarian::class)->name('kesantrian.nilai-harian');

    Route::livewire('keuangan/spp', Spp::class)->name('keuangan.spp');
    Route::livewire('keuangan/haflatul-imtihan', HaflatulImtihan::class)->name('keuangan.haflatul-imtihan');
    Route::livewire('keuangan/tabungan', Tabungan::class)->name('keuangan.tabungan');

    Route::get('export/{type}/excel', [ExportImportController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/{type}/pdf', [ExportImportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('import/{type}/template', [ExportImportController::class, 'downloadTemplate'])->name('import.template');
    Route::post('import/{type}', [ExportImportController::class, 'importExcel'])->name('import.excel');

    Route::livewire('konten/galeri', Galleries::class)->name('konten.galeri');
    Route::livewire('konten/pesan', Contacts::class)->name('konten.pesan');
    Route::livewire('konten/langganan', Subscriptions::class)->name('konten.langganan');

    Route::livewire('admin/lembagas', Lembagas::class)->name('admin.lembagas');
    Route::livewire('admin/users', Users::class)->name('admin.users');
    Route::livewire('admin/roles', Roles::class)->name('admin.roles');
    Route::livewire('admin/settings', Index::class)->name('admin.settings');
    Route::livewire('admin/whatsapp', WhatsappBroadcast::class)->name('admin.whatsapp');
});
