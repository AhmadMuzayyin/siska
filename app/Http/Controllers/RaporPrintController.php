<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\SettingRapor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RaporPrintController extends Controller
{
    public function print(Request $request, int $santriId): Response
    {
        $santri = Santri::query()->with(['kelas', 'lembaga'])->findOrFail($santriId);
        $semester = Semester::query()->active()->with('tahunAkademik')->first();

        $nilais = Nilai::query()
            ->where('santri_id', $santri->id)
            ->when($semester, fn ($q) => $q->where('semester_id', $semester->id))
            ->with(['mapel'])
            ->get();

        $settingRaporMap = SettingRapor::query()
            ->whereIn('mapel_id', $nilais->pluck('mapel_id')->filter())
            ->get()
            ->keyBy('mapel_id');

        $setting = Setting::query()->first();

        // Calculate grades & descriptions
        $totalNilai = 0;
        $mapelRows = [];

        foreach ($nilais as $n) {
            $totalNilai += $n->nilai;
            $mapelId = $n->mapel_id;
            $mapelSetting = $settingRaporMap->get($mapelId);

            $predikatStr = $n->predikat?->value ?? 'C';
            $deskripsi = match ($predikatStr) {
                'A' => $mapelSetting?->deskripsi_a ?? 'Sangat baik dalam penguasaan materi.',
                'B' => $mapelSetting?->deskripsi_b ?? 'Baik dalam penguasaan materi.',
                'C' => $mapelSetting?->deskripsi_c ?? 'Cukup dalam penguasaan materi.',
                'D' => $mapelSetting?->deskripsi_d ?? 'Perlu bimbingan lebih lanjut.',
                default => 'Cukup.',
            };

            $mapelRows[] = [
                'nama' => $n->mapel?->nama ?? '-',
                'kitab' => $n->mapel?->kitab ?? '-',
                'nilai' => $n->nilai,
                'predikat' => $predikatStr,
                'deskripsi' => $deskripsi,
            ];
        }

        $rataRata = count($mapelRows) > 0 ? round($totalNilai / count($mapelRows), 1) : 0;

        $data = [
            'nama' => $santri->nama_lengkap,
            'nisn' => $santri->rfid_uid ?? '-',
            'noinduk' => $santri->noinduk,
            'kelas' => $santri->kelas?->nama ?? '-',
            'lembaga' => $santri->lembaga?->nama ?? 'Lembaga Pendidikan',
            'tahun_akademik' => $semester?->tahunAkademik?->nama ?? date('Y'),
            'semester' => ucfirst($semester?->tipe?->value ?? 'Ganjil'),
            'nilai' => $rataRata,
            'deskripsi' => 'Rata-rata akumulasi nilai santri: '.$rataRata,
            'mapel_rows' => $mapelRows,
            'setting' => $setting,
        ];

        return response()->view('rapor-print', $data);
    }
}
