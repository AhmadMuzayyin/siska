<?php

namespace App\Http\Controllers;

use App\Exports\SppExport;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SppController extends Controller
{
    public function print(Kelas $kelas)
    {
        $kelas = $kelas->load(['santri.spp' => function ($query) {
            $query->whereYear('created_at', date('Y')); // Bisa disesuaikan dengan tahun yang diinginkan
        }]);
        return view('Spp.print', [
            'kelas' => $kelas,
            'santris' => $kelas->santri
        ]);
    }
}
