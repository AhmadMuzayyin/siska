<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
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
            'message' => 'Session created successfully'
        ]);
    }
    public function forget()
    {
        session()->forget('jadwal_pelajaran');
        session()->forget('has_ready');
        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully'
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
        $tahunAkademik = TahunAkademik::where('is_aktif', true)->first();
        $jadwalPelajaran = JadwalPelajaran::where('hari', $hariIndonesia[$hari])->get();
        return view('Absensi.create', [
            'jadwalPelajaran' => $jadwalPelajaran,
            'absensi' => Absensi::where('tanggal', now()->format('Y-m-d'))
                ->where('tahun_akademik_id', $tahunAkademik?->id)
                ->where('jadwal_pelajaran_id', session('jadwal_pelajaran'))
                ->paginate(7),
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noinduk' => 'required|exists:santris,noinduk',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $tahunAkademik = TahunAkademik::where('is_aktif', true)->first();
            $santri = Santri::where('noinduk', $request->noinduk)->first();
            $jamMulai = JadwalPelajaran::find(session('jadwal_pelajaran'))->jam_mulai;
            if (now()->format('H:i') < $jamMulai) {
                return response()->json(['error' => 'Tidak bisa melakukan absen, karena jam pelajaran belum di mulai.'], 400);
            }
            $jamSelesai = JadwalPelajaran::find(session('jadwal_pelajaran'))->jam_selesai;
            if (now()->format('H:i') > $jamSelesai) {
                return response()->json(['error' => 'Tidak bisa melakukan absen, karena jam pelajaran sudah selesai.'], 400);
            }
            $absensi = Absensi::query();
            $absensi->where('tahun_akademik_id', $tahunAkademik->id);
            $absensi->where('santri_id', $santri->id);
            $absensi->where('jadwal_pelajaran_id', session('jadwal_pelajaran'));
            $absensi->where('tanggal', now()->format('Y-m-d'));
            $absensi = $absensi->first();
            if ($absensi) {
                return response()->json(['error' => 'Anda sudah melakukan absen pada mata pelajaran hari ini'], 400);
            }
            Absensi::create([
                'tahun_akademik_id' => $tahunAkademik->id,
                'santri_id' => $santri->id,
                'jadwal_pelajaran_id' => session('jadwal_pelajaran'),
                'tanggal' => now()->format('Y-m-d'),
                'status' => 'Hadir',
            ]);
            return response()->json(['success' => true, 'message' => 'Berhasil melakukan absensi']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
