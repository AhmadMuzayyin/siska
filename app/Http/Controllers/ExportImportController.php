<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\User;
use App\Services\LembagaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportImportController extends Controller
{
    /**
     * Download Excel Template for Import (using unique codes: kode_kelas, kode_mapel, kode_lembaga, nip)
     */
    public function downloadTemplate(string $type, LembagaService $lembagaService): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $activeLembagaId = $lembagaService->getActiveLembagaId();
        $activeLembaga = $lembagaService->current();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        switch ($type) {
            case 'mapel':
                $sheet->fromArray([['kode_mapel', 'nama_mapel', 'kitab', 'kkm', 'kode_lembaga']]);
                $sheet->fromArray([['MPL-001', 'Fiqih', 'Fathul Qorib', '70', $activeLembaga?->kode ?? 'MDTA']], null, 'A2');

                // Reference Sheet
                $refSheet = $spreadsheet->createSheet();
                $refSheet->setTitle('Referensi Lembaga');
                $refSheet->fromArray([['Kode Lembaga', 'Nama Lembaga', 'Jenjang']]);
                $lembagas = Lembaga::query()->active()->get();
                $refRows = [];
                foreach ($lembagas as $l) {
                    $refRows[] = [$l->kode, $l->nama, $l->jenjang];
                }
                $refSheet->fromArray($refRows, null, 'A2');
                $filename = 'template_import_mapel.xlsx';
                break;

            case 'guru':
                $sheet->fromArray([['nip', 'nama_guru', 'email', 'jenis_kelamin', 'telepon', 'alamat']]);
                $sheet->fromArray([['19850101', 'Ustadz Ahmad', 'ahmad@example.com', 'laki_laki', '081234567890', 'Jl. Pendidikan No. 12']], null, 'A2');
                $filename = 'template_import_guru.xlsx';
                break;

            case 'santri':
                $sampleKelas = Kelas::query()
                    ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                    ->first();

                $headers = [
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
                ];

                $sampleData = [
                    '2025001',
                    'Muhammad Ali',
                    'Ali',
                    'laki_laki',
                    'Sumenep',
                    '2010-05-15',
                    '1',
                    'Desa Gadu Barat',
                    'Ahmad',
                    'SMA',
                    'Wiraswasta',
                    'Siti',
                    'SMP',
                    'Ibu Rumah Tangga',
                    '081234567891',
                    $sampleKelas?->kode ?? 'KLS-001',
                    $activeLembaga?->kode ?? ($sampleKelas?->lembaga?->kode ?? 'MDTA'),
                ];

                $sheet->fromArray([$headers]);
                $sheet->fromArray([$sampleData], null, 'A2');

                // Add Reference Sheet for Class Codes & Institution Codes
                $refSheet = $spreadsheet->createSheet();
                $refSheet->setTitle('Referensi Kelas & Lembaga');
                $refSheet->fromArray([['Kode Kelas', 'Nama Kelas', 'Kode Lembaga', 'Nama Lembaga']]);
                $kelases = Kelas::with('lembaga')
                    ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                    ->get();
                $refRows = [];
                foreach ($kelases as $k) {
                    $refRows[] = [$k->kode, $k->nama, $k->lembaga?->kode ?? '-', $k->lembaga?->nama ?? '-'];
                }
                $refSheet->fromArray($refRows, null, 'A2');
                $filename = 'template_import_santri.xlsx';
                break;

            case 'jadwal':
                $sampleKelas = Kelas::query()->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))->first();
                $sampleMapel = Mapel::query()->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id'))->first();
                $sampleGuru = Guru::with('user')->first();

                $sheet->fromArray([['kode_kelas', 'kode_mapel', 'nip_guru', 'hari', 'jam_mulai', 'jam_selesai']]);
                $sheet->fromArray([
                    [
                        $sampleKelas?->kode ?? 'KLS-001',
                        $sampleMapel?->kode ?? 'MPL-001',
                        $sampleGuru?->nip ?? '19850101',
                        'senin',
                        '07:30',
                        '09:00',
                    ],
                ], null, 'A2');

                // Add Reference Sheet
                $refSheet = $spreadsheet->createSheet();
                $refSheet->setTitle('Referensi Kode');
                $refSheet->fromArray([['Kode Kelas', 'Nama Kelas', 'Kode Mapel', 'Nama Mapel', 'NIP Guru', 'Nama Guru']]);

                $kelases = Kelas::query()->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))->get();
                $mapels = Mapel::query()->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id'))->get();
                $gurus = Guru::with('user')->get();

                $maxCount = max(count($kelases), count($mapels), count($gurus));
                $refRows = [];
                for ($idx = 0; $idx < $maxCount; $idx++) {
                    $k = $kelases[$idx] ?? null;
                    $m = $mapels[$idx] ?? null;
                    $g = $gurus[$idx] ?? null;

                    $refRows[] = [
                        $k?->kode ?? '',
                        $k?->nama ?? '',
                        $m?->kode ?? '',
                        $m?->nama ?? '',
                        $g?->nip ?? '',
                        $g?->user?->name ?? '',
                    ];
                }
                $refSheet->fromArray($refRows, null, 'A2');
                $filename = 'template_import_jadwal.xlsx';
                break;

            default:
                abort(404);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename);
    }

    /**
     * Import Excel Data (Maps unique codes to database foreign key integer IDs)
     */
    public function importExcel(Request $request, string $type, LembagaService $lembagaService)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $activeLembagaId = $lembagaService->getActiveLembagaId();
        $file = $request->file('file');
        $reader = IOFactory::createReaderForFile($file->getPathname());
        $spreadsheet = $reader->load($file->getPathname());
        $data = $spreadsheet->getActiveSheet()->toArray();

        if (count($data) <= 1) {
            return back()->with('error', __('File excel kosong atau tidak memiliki baris data.'));
        }

        $headers = array_map('trim', $data[0]);
        $importedCount = 0;

        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
            if (empty(array_filter($row))) {
                continue;
            }

            $mapped = array_combine($headers, array_pad($row, count($headers), null));

            switch ($type) {
                case 'mapel':
                    $namaMapel = $mapped['nama_mapel'] ?? $mapped['nama'] ?? null;
                    if (! empty($namaMapel)) {
                        $kodeLembaga = $mapped['kode_lembaga'] ?? null;
                        $lembagaId = null;
                        if (! empty($kodeLembaga)) {
                            $lembagaId = Lembaga::query()->where('kode', $kodeLembaga)->first()?->id;
                        }
                        $lembagaId = $lembagaId ?? $activeLembagaId;

                        Mapel::query()->create([
                            'kode' => $mapped['kode_mapel'] ?? $mapped['kode'] ?? null,
                            'nama' => $namaMapel,
                            'kitab' => $mapped['kitab'] ?? null,
                            'kkm' => (int) ($mapped['kkm'] ?? 70),
                            'lembaga_id' => $lembagaId,
                        ]);
                        $importedCount++;
                    }
                    break;

                case 'guru':
                    $namaGuru = $mapped['nama_guru'] ?? $mapped['name'] ?? null;
                    $emailGuru = $mapped['email'] ?? null;

                    if (! empty($namaGuru) && ! empty($emailGuru)) {
                        $user = User::query()->where('email', $emailGuru)->first();

                        if (! $user) {
                            $user = User::query()->create([
                                'name' => $namaGuru,
                                'email' => $emailGuru,
                                'password' => Hash::make('password'),
                                'role' => 'guru',
                            ]);
                        }

                        Guru::query()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'nip' => $mapped['nip'] ?? null,
                                'jenis_kelamin' => $mapped['jenis_kelamin'] ?? 'laki_laki',
                                'telepon' => $mapped['telepon'] ?? null,
                                'alamat' => $mapped['alamat'] ?? '-',
                                'whatsapp' => $mapped['telepon'] ?? '081234567890',
                                'status' => 'aktif',
                            ]
                        );
                        $importedCount++;
                    }
                    break;

                case 'santri':
                    $namaLengkap = $mapped['nama_lengkap'] ?? null;
                    if (! empty($namaLengkap)) {
                        // Lookup class by kode_kelas or nama_kelas or ID
                        $kodeKelas = $mapped['kode_kelas'] ?? $mapped['kelas_id'] ?? null;
                        $kelas = null;
                        if ($kodeKelas) {
                            $kelas = Kelas::query()->where('kode', $kodeKelas)->first()
                                ?? Kelas::query()->where('nama', $kodeKelas)->first()
                                ?? Kelas::query()->find($kodeKelas);
                        }

                        if (! $kelas) {
                            $kelas = Kelas::query()
                                ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                                ->first() ?? Kelas::first();
                        }

                        // Lookup lembaga by kode_lembaga or fallback to class lembaga or active lembaga
                        $kodeLembaga = $mapped['kode_lembaga'] ?? null;
                        $lembagaId = null;
                        if ($kodeLembaga) {
                            $lembagaId = Lembaga::query()->where('kode', $kodeLembaga)->first()?->id;
                        }

                        $lembagaId = $lembagaId ?? ($kelas?->lembaga_id ?? $activeLembagaId);

                        Santri::query()->create([
                            'noinduk' => $mapped['noinduk'] ?? 'S'.time().rand(10, 99),
                            'nama_lengkap' => $namaLengkap,
                            'nama_panggilan' => $mapped['nama_panggilan'] ?? explode(' ', $namaLengkap)[0],
                            'jenis_kelamin' => $mapped['jenis_kelamin'] ?? 'laki_laki',
                            'tempat_lahir' => $mapped['tempat_lahir'] ?? '-',
                            'tanggal_lahir' => ! empty($mapped['tanggal_lahir']) ? date('Y-m-d', strtotime($mapped['tanggal_lahir'])) : now()->subYears(10)->toDateString(),
                            'anak_ke' => (int) ($mapped['anak_ke'] ?? 1),
                            'alamat' => $mapped['alamat'] ?? '-',
                            'nama_ayah' => $mapped['nama_ayah'] ?? '-',
                            'pendidikan_ayah' => $mapped['pendidikan_ayah'] ?? '-',
                            'pekerjaan_ayah' => $mapped['pekerjaan_ayah'] ?? '-',
                            'nama_ibu' => $mapped['nama_ibu'] ?? '-',
                            'pendidikan_ibu' => $mapped['pendidikan_ibu'] ?? '-',
                            'pekerjaan_ibu' => $mapped['pekerjaan_ibu'] ?? '-',
                            'telepon_wali' => $mapped['telepon_wali'] ?? '-',
                            'kelas_id' => $kelas->id,
                            'lembaga_id' => $lembagaId,
                            'status' => 'aktif',
                        ]);
                        $importedCount++;
                    }
                    break;

                case 'jadwal':
                    $kodeKelas = $mapped['kode_kelas'] ?? null;
                    $kodeMapel = $mapped['kode_mapel'] ?? null;
                    $nipGuru = $mapped['nip_guru'] ?? null;

                    $kelas = $kodeKelas
                        ? (Kelas::query()->where('kode', $kodeKelas)->first() ?? Kelas::query()->where('nama', $kodeKelas)->first())
                        : null;

                    $mapel = $kodeMapel
                        ? (Mapel::query()->where('kode', $kodeMapel)->first() ?? Mapel::query()->where('nama', $kodeMapel)->first())
                        : null;

                    $guru = $nipGuru
                        ? (Guru::query()->where('nip', $nipGuru)->first() ?? Guru::query()->whereHas('user', fn ($u) => $u->where('email', $nipGuru)->orWhere('name', $nipGuru))->first())
                        : null;

                    $activeSemester = Semester::query()->where('is_active', true)->first() ?? Semester::first();

                    if ($kelas && $mapel && $guru && $activeSemester) {
                        JadwalPelajaran::query()->create([
                            'semester_id' => $activeSemester->id,
                            'kelas_id' => $kelas->id,
                            'mapel_id' => $mapel->id,
                            'guru_id' => $guru->id,
                            'hari' => $mapped['hari'] ?? 'senin',
                            'jam_mulai' => $mapped['jam_mulai'] ?? '07:30',
                            'jam_selesai' => $mapped['jam_selesai'] ?? '09:00',
                        ]);
                        $importedCount++;
                    }
                    break;
            }
        }

        return back()->with('success', __(':count data berhasil di-import dari excel.', ['count' => $importedCount]));
    }

    /**
     * Export Excel Data (Filtered by Active Lembaga)
     */
    public function exportExcel(string $type, LembagaService $lembagaService): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $activeLembagaId = $lembagaService->getActiveLembagaId();

        switch ($type) {
            case 'guru':
                $sheet->fromArray([['No', 'NIP', 'Nama Guru', 'Email', 'Jenis Kelamin', 'Telepon', 'Status']]);
                $gurus = Guru::with('user')->get();
                $rows = [];
                foreach ($gurus as $idx => $g) {
                    $rows[] = [
                        $idx + 1,
                        $g->nip ?? '-',
                        $g->user->name ?? '-',
                        $g->user->email ?? '-',
                        $g->jenis_kelamin?->value ?? '-',
                        $g->telepon ?? '-',
                        $g->status?->value ?? 'aktif',
                    ];
                }
                $sheet->fromArray($rows, null, 'A2');
                $filename = 'export_data_guru.xlsx';
                break;

            case 'santri':
                $sheet->fromArray([['No', 'No. Induk', 'Nama Lengkap', 'Lembaga', 'Kelas', 'Jenis Kelamin', 'Telepon Wali', 'Status']]);
                $santris = Santri::with(['kelas', 'lembaga'])
                    ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                    ->get();
                $rows = [];
                foreach ($santris as $idx => $s) {
                    $rows[] = [
                        $idx + 1,
                        $s->noinduk,
                        $s->nama_lengkap,
                        $s->lembaga?->nama ?? '-',
                        $s->kelas?->nama ?? '-',
                        $s->jenis_kelamin?->value ?? '-',
                        $s->telepon_wali ?? '-',
                        $s->status?->value ?? 'aktif',
                    ];
                }
                $sheet->fromArray($rows, null, 'A2');
                $filename = 'export_data_santri.xlsx';
                break;

            case 'nilai':
                $sheet->fromArray([['No', 'Santri', 'Kelas', 'Mapel', 'Nilai', 'Predikat', 'Semester']]);
                $nilais = Nilai::with(['santri.kelas', 'mapel', 'semester.tahunAkademik'])
                    ->when($activeLembagaId, fn ($query) => $query->whereHas('santri', fn ($s) => $s->where('lembaga_id', $activeLembagaId)))
                    ->get();
                $rows = [];
                foreach ($nilais as $idx => $n) {
                    $rows[] = [
                        $idx + 1,
                        $n->santri?->nama_lengkap ?? '-',
                        $n->santri?->kelas?->nama ?? '-',
                        $n->mapel?->nama ?? '-',
                        $n->nilai,
                        $n->predikat?->value ?? '-',
                        $n->semester?->tahunAkademik?->nama ?? '-',
                    ];
                }
                $sheet->fromArray($rows, null, 'A2');
                $filename = 'export_data_nilai.xlsx';
                break;

            default:
                abort(404);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename);
    }

    /**
     * Export PDF Data (Print View Filtered by Active Lembaga)
     */
    public function exportPdf(string $type, LembagaService $lembagaService): Response
    {
        $activeLembagaId = $lembagaService->getActiveLembagaId();

        switch ($type) {
            case 'guru':
                $title = 'Data Guru';
                $data = Guru::with('user')->get();
                break;
            case 'santri':
                $title = 'Data Santri';
                $data = Santri::with(['kelas', 'lembaga'])
                    ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                    ->get();
                break;
            case 'nilai':
                $title = 'Data Nilai Santri';
                $data = Nilai::with(['santri.kelas', 'mapel', 'semester.tahunAkademik'])
                    ->when($activeLembagaId, fn ($query) => $query->whereHas('santri', fn ($s) => $s->where('lembaga_id', $activeLembagaId)))
                    ->get();
                break;
            default:
                abort(404);
        }

        return response()->view('export-pdf', compact('title', 'type', 'data'));
    }
}
