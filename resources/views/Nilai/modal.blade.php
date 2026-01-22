@php
    $kelasList = \App\Models\Kelas::with('santri')->get();
@endphp
<div x-data="{ kelas_id: '', santri_id: '', santris: [] }" x-init="() => { santris = [] }">
    <div class="mb-2">
        <label for="kelas_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
        <select name="kelas_id" id="kelas_id" x-model="kelas_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
            @change="
                santri_id = '';
                santris = kelas_id ? JSON.parse($refs['kelas_' + kelas_id].value) : [];
            ">
            <option value="" selected disabled>Pilih Kelas</option>
            @foreach ($kelasList as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
            @endforeach
        </select>
        @foreach ($kelasList as $kelas)
            <input type="hidden" x-ref="kelas_{{ $kelas->id }}" value='@json($kelas->santri->map(fn($s) => ['id' => $s->id, 'nama_lengkap' => $s->nama_lengkap]))'>
        @endforeach
    </div>
    <div class="mb-2">
        <label for="santri_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Santri</label>
        <select name="santri_id" id="santri_id" x-model="santri_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="" selected disabled>Pilih Santri</option>
            <template x-for="santri in santris" :key="santri.id">
                <option :value="santri.id" x-text="santri.nama_lengkap"></option>
            </template>
        </select>
    </div>
    <button x-bind:disabled="!santri_id" @click="window.open('/nilai/print/' + santri_id, '_blank')"
        class="px-4 py-2 bg-primary-500 text-white rounded-lg mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
        Print
    </button>
</div>
