<div>
    @if (!$this->hasActiveSemester)
        <div class="w-full bg-amber-50 border-b border-amber-200 dark:bg-amber-950/30 dark:border-amber-800/50">
            <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/50">
                    <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex flex-1 flex-wrap items-center gap-1 text-sm">
                    <span class="font-semibold text-amber-800 dark:text-amber-200">
                        {{ __('Tidak ada semester aktif.') }}
                    </span>
                    <span class="text-amber-700 dark:text-amber-300">
                        {{ __('Semua fitur input data (absensi, nilai, pembayaran, gaji) tidak dapat digunakan hingga semester baru dibuka.') }}
                    </span>
                </div>

                <a
                    href="{{ route('akademik.tahun-akademik') }}"
                    wire:navigate
                    class="shrink-0 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:bg-amber-700 dark:hover:bg-amber-600 transition-colors"
                >
                    {{ __('Buka Semester Baru') }} &rarr;
                </a>
            </div>
        </div>
    @endif
</div>
