<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Langganan Newsletter') }}</flux:heading>
            <flux:subheading>{{ __('Daftar email yang berlangganan informasi pesantren dan berita kegiatan.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-3">
            <flux:button variant="primary" icon="paper-airplane" wire:click="openBroadcastModal" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Kirim Blast Newsletter') }}
            </flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:text class="text-xs font-medium text-zinc-500">{{ __('Total Pelanggan Active') }}</flux:text>
                <flux:icon name="at-symbol" class="size-5 text-emerald-600" />
            </div>
            <flux:heading size="xl" class="mt-2 font-bold">{{ number_format($this->totalSubscribers) }}</flux:heading>
            <flux:text class="mt-1 text-xs text-emerald-600">{{ __('Email terverifikasi') }}</flux:text>
        </div>
    </div>

    <div class="flex items-center justify-between gap-4">
        <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari email pelanggan...') }}" icon="magnifying-glass" class="w-full sm:w-72" />
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Email Pelanggan') }}</flux:table.column>
                <flux:table.column>{{ __('Tanggal Berlangganan') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $subscription)
                    <flux:table.row wire:key="subscription-{{ $subscription->id }}">
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:icon name="envelope" class="size-4 text-zinc-400" />
                                <span>{{ $subscription->email }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $subscription->created_at->format('d M Y H:i') }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="trash"
                                class="text-red-600 hover:text-red-700"
                                wire:click="delete({{ $subscription->id }})"
                                wire:confirm="{{ __('Yakin ingin menghapus langganan ini?') }}"
                            />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data berlangganan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="broadcast-modal" class="md:w-128" @close="$set('subjek', ''); $set('pesan', '')">
        <form wire:submit="sendBroadcast" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ __('Kirim Blast Newsletter') }}</flux:heading>
                <flux:subheading>{{ __('Kirim email pengumuman atau newsletter ke :count pelanggan aktif.', ['count' => $this->totalSubscribers]) }}</flux:subheading>
            </div>

            <flux:input wire:model="subjek" :label="__('Subjek Newsletter')" placeholder="misal: Pengumuman Kegiatan & Pendaftaran Baru" />

            <flux:textarea wire:model="pesan" rows="6" :label="__('Isi Pesan Newsletter')" placeholder="Tuliskan isi pengumuman newsletter di sini..." />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    <flux:icon name="paper-airplane" class="size-4 me-1.5" />
                    {{ __('Kirim Newsletter') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
