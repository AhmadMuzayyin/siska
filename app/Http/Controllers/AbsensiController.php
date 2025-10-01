<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function session(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jadwal_pelajaran' => 'required|exists:jadwal_pelajarans,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $jadwal = JadwalPelajaran::find($request->jadwal_pelajaran);
            if (! $jadwal) {
                return response()->json(['error' => 'Jadwal pelajaran tidak ditemukan'], 400);
            }
            session(['jadwal_pelajaran_aktif' => $jadwal->id]);

            return response()->json([
                'success' => true,
                'message' => 'Session created successfully',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan: '.$th->getMessage(),
            ], 500);
        }
    }

    public function forget()
    {
        session()->forget('jadwal_pelajaran_aktif');

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran aktif berhasil dihapus',
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
                ->where('jadwal_pelajaran_id', cache('jadwal_pelajaran_aktif'))
                ->paginate(7),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'absensi' => 'required|array',
            'absensi.*.noinduk' => 'required|exists:santris,noinduk',
            'absensi.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            date_default_timezone_set('Asia/Jakarta');
            Carbon::setLocale('id');
            $jadwalPelajaran = JadwalPelajaran::find(session('jadwal_pelajaran_aktif'));
            if (! $jadwalPelajaran) {
                return response()->json(['error' => 'Jadwal pelajaran tidak ditemukan'], 400);
            }

            $currentTime = now();
            $startTime = Carbon::createFromTimeString($jadwalPelajaran->jam_mulai);
            $endTime = Carbon::createFromTimeString($jadwalPelajaran->jam_selesai);
            if ($endTime->hour === 0 && $endTime->minute === 0 && $endTime->second === 0) {
                $endTime->addDay();
            }
            if ($currentTime->lt($startTime)) {
                return response()->json([
                    'error' => 'Tidak bisa melakukan absen, karena jam pelajaran belum dimulai.',
                ], 400);
            }
            if ($currentTime->gt($endTime)) {
                return response()->json([
                    'error' => 'Tidak bisa melakukan absen, karena jam pelajaran sudah selesai.',
                ], 400);
            }

            // Get active semester
            $semester = Semester::where('is_aktif', true)->first();
            if (! $semester) {
                return response()->json(['error' => 'Semester aktif tidak ditemukan'], 400);
            }

            // Tanggal hari ini
            $today = now()->format('Y-m-d');

            // Begin transaction
            DB::beginTransaction();

            try {
                foreach ($request->absensi as $data) {
                    $santri = Santri::where('noinduk', $data['noinduk'])->first();
                    $existingAbsensi = Absensi::where([
                        'semester_id' => $semester->id,
                        'santri_id' => $santri->id,
                        'jadwal_pelajaran_id' => $jadwalPelajaran->id,
                        'tanggal' => $today,
                    ])->first();

                    if ($existingAbsensi) {
                        $existingAbsensi->update([
                            'status' => ucfirst($data['status']),
                        ]);
                    } else {
                        Absensi::create([
                            'semester_id' => $semester->id,
                            'santri_id' => $santri->id,
                            'jadwal_pelajaran_id' => $jadwalPelajaran->id,
                            'tanggal' => $today,
                            'status' => ucfirst($data['status']),
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menyimpan data absensi',
                ]);
            } catch (\Exception $e) {
                dd($e->getMessage());
                DB::rollBack();
                throw $e;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Terjadi kesalahan: '.$th->getMessage(),
            ], 500);
        }
    }
}
