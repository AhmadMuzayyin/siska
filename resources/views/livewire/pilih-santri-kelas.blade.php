<div>
    <div class="mb-2">
        <label for="kelas_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
        <select wire:model.lazy="kelas_id" id="kelas_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="" selected disabled>Pilih Kelas</option>
            @foreach ($kelasList as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label for="santri_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Santri</label>
        <select wire:model="santri_id" id="santri_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="" selected disabled>Pilih Santri</option>
            @foreach ($this->santris as $santri)
                <option value="{{ $santri->id }}">{{ $santri->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    <button wire:click="$emit('printSantri', santri_id)" @disabled('!santri_id')
        class="px-4 py-2 bg-primary-500 text-white rounded-lg mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
        Print
    </button>
</div>
