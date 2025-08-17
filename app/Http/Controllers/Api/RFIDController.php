<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RFIDController extends Controller
{
    public function scan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
        $uid = $request->input('uid');

        // 1. cek apakah UID sudah terdaftar di santri
        $santri = Santri::where('rfid_uid', $uid)->first();
        if ($santri) {
            // 2. cek jadwal aktif sesuai kelas santri
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

            $currentTime = now()->format('H:i:s');

            $jadwal = JadwalPelajaran::where('kelas_id', $santri->kelas_id)
                ->where('hari', $hariIndonesia[$hari])
                ->where('jam_mulai', '<=', $currentTime)
                ->where('jam_selesai', '>=', $currentTime)
                ->first();
            if (!$jadwal) {
                return response()->json([
                    'status' => 'failed',
                    'type' => 'invalid_time',
                    'message' => 'Tidak ada kelas'
                ]);
            }
            // 3. cek semester aktif
            $semester = Semester::where('is_aktif', true)->first();
            if (!$semester) {
                return response()->json([
                    'status' => 'failed',
                    'type' => 'error',
                    'message' => 'Semester aktif tidak ditemukan'
                ]);
            }

            // 4. simpan absensi
            $today = now()->toDateString();
            DB::beginTransaction();
            try {
                $absensi = new Absensi();
                if ($absensi->where('semester_id', $semester->id)
                    ->where('santri_id', $santri->id)
                    ->where('jadwal_pelajaran_id', $jadwal->id)
                    ->where('tanggal', $today)
                    ->exists()
                ) {
                    return response()->json([
                        'status' => 'failed',
                        'type' => 'already_absent',
                        'message' => "Santri {$santri->nama_lengkap} sudah absen hari ini"
                    ]);
                }
                $absensi->updateOrCreate(
                    [
                        'semester_id' => $semester->id,
                        'santri_id' => $santri->id,
                        'jadwal_pelajaran_id' => $jadwal->id,
                        'tanggal' => $today,
                    ],
                    [
                        'status' => 'Hadir'
                    ]
                );
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'type' => 'absensi',
                    'message' => "Absensi berhasil untuk {$santri->nama_lengkap} di kelas {$jadwal->kelas->nama}"
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        // 2. kalau belum terdaftar → cek apakah ada santri yang menunggu assign kartu
        $santriId = Cache::pull('register_rfid_santri_id');
        if ($santriId) {
            $santri = Santri::find($santriId);
            if ($santri) {
                $santri->rfid_uid = $uid;
                $santri->save();

                return response()->json([
                    'status' => 'success',
                    'type' => 'register',
                    'message' => "UID berhasil didaftarkan ke {$santri->nama_lengkap}"
                ]);
            }
        }

        // 3. kalau tidak ada santri yg nunggu → kartu dianggap tidak terdaftar
        return response()->json([
            'status' => 'failed',
            'type' => 'unregistered',
            'message' => 'Kartu tidak terdaftar!'
        ]);
    }
}
