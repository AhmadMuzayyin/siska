@php
    $kelas = \App\Models\Kelas::all();
@endphp
<div x-data="{ kelasId: '' }">
    <div class="mb-2">
        <label for="santri_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
        <select name="santri_id" id="santri_id" x-model="kelasId"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="" selected disabled>Pilih Kelas</option>
            @foreach ($kelas as $kls)
                <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
            @endforeach
        </select>
    </div>
    <button x-bind:disabled="!kelasId" @click="window.open('/spp/print/' + kelasId, '_blank')"
        class="px-4 py-2 bg-primary-500 text-white rounded-lg mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
        Print
    </button>
</div>
