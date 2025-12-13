<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\GajiGuru;
use App\Models\Guru;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbsenGuruController extends Controller
{
    public function create()
    {
        $teachers = Guru::with('user')->get();
        $absensiGuru = AbsensiGuru::all();

        return view('Absensi_Guru.create', compact('teachers', 'absensiGuru'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'absensi' => 'required|array',
            'absensi.*.guru_id' => 'required|exists:gurus,id',
            'absensi.*.status' => 'required|in:hadir,izin',
        ]);
        try {
            date_default_timezone_set('Asia/Jakarta');
            $validator = $validator->validate();

            $semester = Semester::where('is_aktif', true)->first();
            if (! $semester) {
                return response()->json([
                    'status' => false,
                    'message' => 'Semester aktif tidak ditemukan',
                ], 422);
            }

            $today = now()->format('Y-m-d');

            $guruIds = collect($validator['absensi'])->pluck('guru_id')->toArray();

            $sudahAbsen = AbsensiGuru::where('semester_id', $semester->id)
                ->whereIn('guru_id', $guruIds)
                ->where('tanggal', $today)
                ->pluck('guru_id')
                ->toArray();

            if (! empty($sudahAbsen)) {
                $namaGuru = Guru::with('user')->whereIn('id', $sudahAbsen)->get()->pluck('user.name')->implode(', ');

                return response()->json([
                    'status' => false,
                    'message' => "Guru berikut sudah melakukan absensi hari ini: {$namaGuru}",
                ], 422);
            }

            DB::beginTransaction();
            foreach ($validator['absensi'] as $data) {
                AbsensiGuru::create([
                    'semester_id' => $semester->id,
                    'guru_id' => $data['guru_id'],
                    'status' => ucfirst($data['status']),
                    'tanggal' => $today,
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Absensi Guru berhasil disimpan.',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 422);
        }
    }

    public function report(Request $request)
    {

        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $tanggal_awal = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $tanggal_akhir = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $absensiGurus = AbsensiGuru::with('guru.user')
            ->select('guru_id', DB::raw('COUNT(*) as total_absensi'),
                DB::raw("SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as jumlah_hadir"),
                DB::raw("SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as jumlah_izin"))
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->groupBy('guru_id')
            ->get();
        $gajiGuru = GajiGuru::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('guru_id', $absensiGurus->pluck('guru_id'))
            ->get()
            ->keyBy('guru_id');

        return view('Absensi_Guru.report', compact('absensiGurus', 'bulan', 'tahun', 'gajiGuru'));
    }
}
