<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'nama_panggilan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'kelas' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'nama_ayah' => 'required',
            'pendidikan_ayah' => 'required',
            'pekerjaan_ayah' => 'required',
            'telepon_ayah' => 'required',
            'nama_ibu' => 'required',
            'pendidikan_ibu' => 'required',
            'pekerjaan_ibu' => 'required',
            'telepon_ibu' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama Lengkap harus diisi',
            'nama_panggilan.required' => 'Nama Panggilan harus diisi',
            'tempat_lahir.required' => 'Tempat Lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
            'kelas.required' => 'Pilih Kelas',
            'alamat.required' => 'Alamat harus diisi',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
            'nama_ayah.required' => 'Nama Ayah harus diisi',
            'pendidikan_ayah.required' => 'Pendidikan Ayah harus diisi',
            'pekerjaan_ayah.required' => 'Pekerjaan Ayah harus diisi',
            'telepon_ayah.required' => 'Telepon Ayah harus diisi',
            'nama_ibu.required' => 'Nama Ibu harus diisi',
            'pendidikan_ibu.required' => 'Pendidikan Ibu harus diisi',
            'pekerjaan_ibu.required' => 'Pekerjaan Ibu harus diisi',
            'telepon_ibu.required' => 'Telepon Ibu harus diisi',
        ]);
        try {
            $validated['noinduk'] = 'S' . date('Y') . sprintf('%04d', Santri::count() + 1);
            $validated['kelas_id'] = $validated['kelas'];
            unset($validated['kelas']);
            Santri::create($validated);
            return redirect()->route('index')->with('success', 'Berhasil mendaftar');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mendaftar');
        }
    }
}
