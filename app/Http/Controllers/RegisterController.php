<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'kelas' => 'required|exists:kelas,id',
            'alamat' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'nama_ayah' => 'required|string|max:255',
            'pendidikan_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pendidikan_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'telepon_wali' => 'required|numeric',
            'anak_ke' => 'required|numeric|min:1',
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
            'nama_ibu.required' => 'Nama Ibu harus diisi',
            'pendidikan_ibu.required' => 'Pendidikan Ibu harus diisi',
            'pekerjaan_ibu.required' => 'Pekerjaan Ibu harus diisi',
            'telepon_wali.required' => 'Telepon wali harus diisi',
            'telepon_wali.numeric' => 'Telepon wali harus berupa angka',
            'jenis_kelamin.in' => 'Jenis Kelamin harus Laki-laki atau Perempuan',
            'tanggal_lahir.date' => 'Tanggal Lahir harus berupa tanggal',
            'kelas.exists' => 'Kelas tidak ditemukan',
            'nama_lengkap.string' => 'Nama Lengkap harus berupa string',
            'nama_panggilan.string' => 'Nama Panggilan harus berupa string',
            'tempat_lahir.string' => 'Tempat Lahir harus berupa string',
            'alamat.string' => 'Alamat harus berupa string',
            'nama_ayah.string' => 'Nama Ayah harus berupa string',
            'nama_ibu.string' => 'Nama Ibu harus berupa string',
            'pendidikan_ayah.string' => 'Pendidikan Ayah harus berupa string',
            'pekerjaan_ayah.string' => 'Pekerjaan Ayah harus berupa string',
            'pendidikan_ibu.string' => 'Pendidikan Ibu harus berupa string',
            'pekerjaan_ibu.string' => 'Pekerjaan Ibu harus berupa string',
            'telepon_wali.max' => 'Telepon wali maksimal 255 digit',
            'nama_lengkap.max' => 'Nama Lengkap maksimal 255 karakter',
            'nama_panggilan.max' => 'Nama Panggilan maksimal 255 karakter',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'nama_ayah.max' => 'Nama Ayah maksimal 255 karakter',
            'nama_ibu.max' => 'Nama Ibu maksimal 255 karakter',
            'pendidikan_ayah.max' => 'Pendidikan Ayah maksimal 255 karakter',
            'pekerjaan_ayah.max' => 'Pekerjaan Ayah maksimal 255 karakter',
            'pendidikan_ibu.max' => 'Pendidikan Ibu maksimal 255 karakter',
            'pekerjaan_ibu.max' => 'Pekerjaan Ibu maksimal 255 karakter',
            'anak_ke.numeric' => 'Anak ke harus berupa angka',
            'anak_ke.min' => 'Anak ke minimal 1',
            'anak_ke.required' => 'Anak ke harus diisi',
        ]);
        try {
            $validated['noinduk'] = 'S' . date('Y') . sprintf('%04d', Santri::count() + 1);
            $validated['kelas_id'] = $validated['kelas'];
            unset($validated['kelas']);
            $now = Carbon::now();
            if ($validated['tanggal_lahir'] == $now->format('Y-m-d')) {
                return redirect()->back()->with('error', 'Tanggal Lahir tidak valid')->withInput();
            }
            $umur = $now->year - Carbon::parse($validated['tanggal_lahir'])->year;
            if ($umur < 4) {
                return redirect()->back()->with('error', 'Umur minimal 4 tahun')->withInput();
            }
            $validated['telepon_wali'] = substr($validated['telepon_wali'], 0, 1) === '0' ? '62' . substr($validated['telepon_wali'], 1) : (substr($validated['telepon_wali'], 0, 1) === '8' ? '62' . $validated['telepon_wali'] : $validated['telepon_wali']);
            Santri::create($validated);

            return redirect()->back()->with('success', 'Berhasil melakukan pendaftaran, silahkan konfirmasi ke admin');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal melakukan pendaftaran')->withInput();
        }
    }
}
