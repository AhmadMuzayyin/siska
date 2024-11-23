<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Semester;

class JadwalPelajaranController extends Controller
{
    public function print()
    {
        $semester = Semester::where('is_aktif', true)->first()->id;
        $jadwalPelajaran = JadwalPelajaran::with('mapel', 'kelas', 'guru', 'semester')->where('semester_id', $semester)->get();
        $kelas = Kelas::with('waliKelas')->get();

        return view('Jadwal.print', compact('jadwalPelajaran', 'kelas'));
    }
}
