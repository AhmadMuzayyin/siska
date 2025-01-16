@php
    $guruId = auth()->user()->guru->id;
    $santris = \App\Models\Santri::with('kelas', 'nilai.mapel', 'nilai.semester.tahunAkademik')
        ->whereHas('kelas', function ($queryKelas) use ($guruId) {
            $queryKelas->whereHas('waliKelas', function ($queryWaliKelas) use ($guruId) {
                $queryWaliKelas->where('guru_id', $guruId);
            });
        })
        ->get();
@endphp

<div x-data='{
    santriId: "",
    santris: {!! json_encode($santris) !!}
}'>
    <!-- Select Input -->
    <div class="mb-4">
        <label for="santriId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Santri</label>
        <select name="santriId" id="santriId" x-model="santriId"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="" selected disabled>Pilih Santri</option>
            @foreach ($santris as $santri)
                <option value="{{ $santri->id }}">{{ $santri->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabel -->
    <div x-show="santriId" class="overflow-x-auto shadow-md mt-4">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Mata Pelajaran</th>
                    <th class="px-4 py-2 border">Kitab</th>
                    <th class="px-4 py-2 border">KKM</th>
                    <th class="px-4 py-2 border">Predikat</th>
                    <th class="px-4 py-2 border">Nilai Angka</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filter santri berdasarkan santriId -->
                <template x-for="santri in santris.filter(item => item.id == santriId)" :key="santri.id">
                    <template x-for="nilai in santri.nilai" :key="nilai.id">
                        <tr>
                            <td class="px-4 py-2 border" x-text="nilai.mapel.nama"></td>
                            <td class="px-4 py-2 border" x-text="nilai.mapel.kitab"></td>
                            <td class="px-4 py-2 border" x-text="60"></td>
                            <td class="px-4 py-2 border" x-text="nilai.predikat"></td>
                            <td class="px-4 py-2 border" x-text="nilai.nilai"></td>
                        </tr>
                    </template>
                </template>
                <tr>
                    <td class="px-4 py-2 border" colspan="4">Jumlah</td>
                    <td class="px-4 py-2 border">
                        <template x-for="santri in santris.filter(item => item.id == santriId)" :key="santri.id">
                            <span x-text="santri.nilai.reduce((sum, item) => sum + item.nilai, 0)"></span>
                        </template>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border" colspan="4">RATA-RATA</td>
                    <td class="px-4 py-2 border">
                        <template x-for="santri in santris.filter(item => item.id == santriId)" :key="santri.id">
                            <span
                                x-text="(santri.nilai.reduce((sum, item) => sum + item.nilai, 0) / santri.nilai.length).toFixed(2)"></span>
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
