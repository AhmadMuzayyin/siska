<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiGuru;
use App\Models\Guru;
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

        $semester = Semester::where('is_aktif', true)->first();
        if (!$semester) {
            return response()->json([
                'status' => 'failed',
                'type' => 'error',
                'message' => 'Semester aktif tidak ditemukan'
            ]);
        }

        $santri = Santri::where('rfid_uid', $uid)->first();
        if ($santri) {
            return $this->absensiSantri($santri, $semester);
        }

        $guru = Guru::where('rfid_uid', $uid)->first();
        if ($guru) {
            return $this->absensiGuru($guru, $semester);
        }

        $santriId = Cache::pull('register_rfid_santri_id');
        if ($santriId) {
            $santri = Santri::find($santriId);
            if ($santri) {
                $santri->rfid_uid = $uid;
                $santri->save();

                return response()->json([
                    'status' => 'success',
                    'type' => 'register',
                    'message' => "UID berhasil didaftarkan ke santri {$santri->nama_lengkap}"
                ]);
            }
        }

        $guruId = Cache::pull('register_rfid_guru_id');
        if ($guruId) {
            $guru = Guru::find($guruId);
            if ($guru) {
                $guru->rfid_uid = $uid;
                $guru->save();

                return response()->json([
                    'status' => 'success',
                    'type' => 'register',
                    'message' => "UID berhasil didaftarkan ke guru {$guru->user->name}"
                ]);
            }
        }

        return response()->json([
            'status' => 'failed',
            'type' => 'unregistered',
            'message' => 'Kartu tidak terdaftar!'
        ]);
    }

    private function absensiSantri(Santri $santri, Semester $semester)
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

        $today = now()->toDateString();
        DB::beginTransaction();
        try {
            $exists = Absensi::where('semester_id', $semester->id)
                ->where('santri_id', $santri->id)
                ->where('jadwal_pelajaran_id', $jadwal->id)
                ->where('tanggal', $today)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'failed',
                    'type' => 'already_absent',
                    'message' => "Santri {$santri->nama_lengkap} sudah absen hari ini"
                ]);
            }

            Absensi::create([
                'semester_id' => $semester->id,
                'santri_id' => $santri->id,
                'jadwal_pelajaran_id' => $jadwal->id,
                'tanggal' => $today,
                'status' => 'Hadir'
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'type' => 'absensi_santri',
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

    private function absensiGuru(Guru $guru, Semester $semester)
    {
        $today = now()->toDateString();

        DB::beginTransaction();
        try {
            $exists = AbsensiGuru::where('semester_id', $semester->id)
                ->where('guru_id', $guru->id)
                ->where('tanggal', $today)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'failed',
                    'type' => 'already_absent',
                    'message' => "Guru {$guru->user->name} sudah absen hari ini"
                ]);
            }

            AbsensiGuru::create([
                'semester_id' => $semester->id,
                'guru_id' => $guru->id,
                'tanggal' => $today,
                'status' => 'Hadir'
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'type' => 'absensi_guru',
                'message' => "Absensi berhasil untuk Guru {$guru->user->name}"
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
}
