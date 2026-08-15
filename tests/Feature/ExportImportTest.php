<?php

use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

test('admin can download template and import santri using unique codes', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lembaga = Lembaga::factory()->create(['kode' => 'MDTA', 'nama' => 'MDTA Arroqy']);
    $kelas = Kelas::factory()->for($lembaga)->create(['kode' => 'KLS-1A', 'nama' => 'Kelas 1A']);

    // 1. Download template
    $this->actingAs($admin)
        ->get(route('import.template', 'santri'))
        ->assertOk();

    // 2. Prepare import file with unique codes (kode_kelas and kode_lembaga)
    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([
        [
            'noinduk',
            'nama_lengkap',
            'nama_panggilan',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'anak_ke',
            'alamat',
            'nama_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'nama_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'telepon_wali',
            'kode_kelas',
            'kode_lembaga',
        ],
        [
            '2026001',
            'Ahmad Fulan',
            'Fulan',
            'laki_laki',
            'Sumenep',
            '2015-01-01',
            '1',
            'Gadu Barat',
            'Bapak Fulan',
            'SMA',
            'Tani',
            'Ibu Fulan',
            'SMP',
            'Wirausaha',
            '08123456789',
            'KLS-1A',
            'MDTA',
        ],
    ]);

    $tempFile = tempnam(sys_get_temp_dir(), 'import_test_').'.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFile);

    $file = new UploadedFile($tempFile, 'import_santri.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

    $this->actingAs($admin)
        ->post(route('import.excel', 'santri'), ['file' => $file])
        ->assertRedirect();

    $santri = Santri::query()->where('noinduk', '2026001')->first();

    expect($santri)->not->toBeNull()
        ->and($santri->nama_lengkap)->toBe('Ahmad Fulan')
        ->and($santri->kelas_id)->toBe($kelas->id)
        ->and($santri->lembaga_id)->toBe($lembaga->id);

    @unlink($tempFile);
});

test('admin can import mapel using unique kode_mapel and kode_lembaga', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lembaga = Lembaga::factory()->create(['kode' => 'TPQ']);

    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray([
        ['kode_mapel', 'nama_mapel', 'kitab', 'kkm', 'kode_lembaga'],
        ['MPL-TAJWID', 'Tajwid & Makhraj', 'Taisirud Talamid', '75', 'TPQ'],
    ]);

    $tempFile = tempnam(sys_get_temp_dir(), 'import_mapel_').'.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempFile);

    $file = new UploadedFile($tempFile, 'import_mapel.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

    $this->actingAs($admin)
        ->post(route('import.excel', 'mapel'), ['file' => $file])
        ->assertRedirect();

    $mapel = Mapel::query()->where('nama', 'Tajwid & Makhraj')->first();

    expect($mapel)->not->toBeNull()
        ->and($mapel->kode)->toBe('MPL-TAJWID')
        ->and($mapel->lembaga_id)->toBe($lembaga->id);

    @unlink($tempFile);
});
