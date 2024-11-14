<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Santri;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function print(Santri $santri)
    {
        $nilai = Nilai::with('tahunAkademik', 'santri', 'jadwalPelajaran')->where('santri_id', $santri->id)->get();
        $absensi = Absensi::where('santri_id', $santri->id)->get();
        return view('Nilai.print', compact('nilai', 'absensi'));
    }
}
