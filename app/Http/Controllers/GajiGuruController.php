<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\GajiGuru;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GajiGuruController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'gaji' => 'required|array',
            'gaji.*.guru_id' => 'required|exists:gurus,id',
            'gaji.*.bisyaroh' => 'required|numeric|min:0',
        ]);

        $semesterAktif = Semester::where('is_aktif', true)->first();

        if (! $semesterAktif) {
            return response()->json([
                'error' => 'Semester aktif tidak ditemukan',
            ], 404);
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = Carbon::create($tahun, $bulan, 1);

        try {
            $tanggalMinimal = Carbon::create($tahun, $bulan, 30);
            if (Carbon::now()->lessThan($tanggalMinimal)) {
                return response()->json([
                    'error' => 'Gaji untuk bulan '.Carbon::create($tahun, $bulan)->format('F Y').' hanya bisa diinput mulai tanggal 30.',
                ], 422);
            }

            foreach ($request->gaji as $item) {
                $jumlahHadirDb = AbsensiGuru::where('guru_id', $item['guru_id'])
                    ->where('semester_id', $semesterAktif->id)
                    ->where('status', 'Hadir')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->count();

                $bisyaroh = (float) $item['bisyaroh'];
                $gajiPerHari = $bisyaroh / 26;
                $totalGaji = round($gajiPerHari * $jumlahHadirDb);

                GajiGuru::updateOrCreate(
                    [
                        'guru_id' => $item['guru_id'],
                        'semester_id' => $semesterAktif->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'bisyaroh' => $bisyaroh,
                        'jumlah_hadir' => $jumlahHadirDb,
                        'total_gaji' => $totalGaji,
                    ]
                );
            }

            return response()->json([
                'message' => 'Data gaji guru berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function export()
    {
        // Logic to export Gaji Guru data
    }
}
