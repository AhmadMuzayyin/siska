<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function session(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_pelajaran' => 'required|exists:jadwal_pelajarans,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        session(['jadwal_pelajaran' => $request->jadwal_pelajaran]);
        session(['has_ready' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Session created successfully',
        ]);
    }

    public function forget()
    {
        session()->forget('jadwal_pelajaran');
        session()->forget('has_ready');

        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully',
        ]);
    }

    public function create()
    {
        $hari = now()->format('l');
        $hariIndonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $semester = Semester::where('is_aktif', true)->first();
        $jadwalPelajaran = JadwalPelajaran::where('hari', $hariIndonesia[$hari])->get();

        return view('Absensi.create', [
            'jadwalPelajaran' => $jadwalPelajaran,
            'absensi' => Absensi::where('tanggal', now()->format('Y-m-d'))
                ->where('semester_id', $semester?->id)
                ->where('jadwal_pelajaran_id', session('jadwal_pelajaran'))
                ->paginate(7),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'absensi' => 'required|array',
            'absensi.*.noinduk' => 'required|exists:santris,noinduk',
            'absensi.*.status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Validasi waktu absensi
            $jadwalPelajaran = JadwalPelajaran::find(session('jadwal_pelajaran'));
            if (!$jadwalPelajaran) {
                return response()->json(['error' => 'Jadwal pelajaran tidak ditemukan'], 400);
            }

            $currentTime = now()->format('H:i');
            if ($currentTime < $jadwalPelajaran->jam_mulai) {
                return response()->json([
                    'error' => 'Tidak bisa melakukan absen, karena jam pelajaran belum dimulai.'
                ], 400);
            }

            if ($currentTime > $jadwalPelajaran->jam_selesai) {
                return response()->json([
                    'error' => 'Tidak bisa melakukan absen, karena jam pelajaran sudah selesai.'
                ], 400);
            }

            // Get active semester
            $semester = Semester::where('is_aktif', true)->first();
            if (!$semester) {
                return response()->json(['error' => 'Semester aktif tidak ditemukan'], 400);
            }

            // Tanggal hari ini
            $today = now()->format('Y-m-d');

            // Begin transaction
            DB::beginTransaction();

            try {
                foreach ($request->absensi as $data) {
                    $santri = Santri::where('noinduk', $data['noinduk'])->first();

                    // Check if attendance already exists
                    $existingAbsensi = Absensi::where([
                        'semester_id' => $semester->id,
                        'santri_id' => $santri->id,
                        'jadwal_pelajaran_id' => session('jadwal_pelajaran'),
                        'tanggal' => $today
                    ])->first();

                    if ($existingAbsensi) {
                        // Update existing attendance
                        $existingAbsensi->update([
                            'status' => ucfirst($data['status'])
                        ]);
                    } else {
                        // Create new attendance
                        Absensi::create([
                            'semester_id' => $semester->id,
                            'santri_id' => $santri->id,
                            'jadwal_pelajaran_id' => session('jadwal_pelajaran'),
                            'tanggal' => $today,
                            'status' => ucfirst($data['status'])
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menyimpan data absensi'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }
}
