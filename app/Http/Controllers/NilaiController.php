<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Santri;

class NilaiController extends Controller
{
    public function print(Santri $santri)
    {
        $nilai = Nilai::join('semesters', 'nilais.semester_id', '=', 'semesters.id')
            ->join('tahun_akademiks', 'semesters.tahun_akademik_id', '=', 'tahun_akademiks.id')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id')
            ->join('santris', 'nilais.santri_id', '=', 'santris.id')
            ->where('semesters.is_aktif', true)
            ->where('santri_id', $santri->id)
            ->get();
        $absensi = Absensi::where('santri_id', $santri->id)->get();

        return view('Nilai.print', compact('nilai', 'absensi'));
    }
}
