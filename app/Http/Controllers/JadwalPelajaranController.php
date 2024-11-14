<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function print()
    {
        $jadwalPelajaran = JadwalPelajaran::with('mapel', 'kelas', 'guru', 'tahunAkademik')->where('tahun_akademik_id', TahunAkademik::where('is_aktif', true)->first()->id)->get();
        return view('Jadwal.print', compact('jadwalPelajaran'));
    }
}
