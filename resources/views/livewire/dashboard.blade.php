<div class="flex flex-col gap-6">
    {{-- ApexCharts CDN --}}
    @assets
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endassets

    @if (auth()->user()->role === \App\Enums\UserRole::Santri)
        {{-- ================= PORTAL SANTRI & WALI DASHBOARD ================= --}}
        @php
            $santri = $this->currentSantri;
        @endphp

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl" class="text-2xl font-bold">{{ __('Portal Santri & Wali') }}</flux:heading>
                <flux:subheading class="mt-1">
                    {{ __('Selamat datang kembali, :name.', ['name' => auth()->user()->name]) }}
                    @if ($santri)
                        &bull; <span class="font-semibold text-emerald-600 dark:text-emerald-400">NIS: {{ $santri->noinduk }}</span>
                    @endif
                </flux:subheading>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}
                </span>
            </div>
        </div>

        @if (! $santri)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-200">
                <flux:icon name="exclamation-circle" class="size-6 text-amber-600 mb-2" />
                <h4 class="font-bold text-sm">{{ __('Data Santri Belum Terhubung') }}</h4>
                <p class="text-xs mt-1">{{ __('Akun pengguna Anda belum ditautkan ke data Santri aktif. Silakan hubungi pengurus atau admin lembaga.') }}</p>
            </div>
        @else
            {{-- Profil & Key Stats Cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">{{ __('Kelas & Lembaga') }}</div>
                    <div class="mt-2 text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $santri->kelas?->nama ?? '-' }}
                    </div>
                    <div class="mt-1 text-xs text-emerald-600 font-semibold dark:text-emerald-400">
                        {{ $santri->lembaga?->nama ?? '-' }}
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">{{ __('Status Santri') }}</div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            {{ ucfirst($santri->status->value ?? 'Aktif') }}
                        </span>
                    </div>
                    <div class="mt-2 text-[11px] text-zinc-500">Wali: {{ $santri->nama_ayah ?: ($santri->nama_ibu ?: '-') }}</div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">{{ __('Total Kehadiran') }}</div>
                    <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">
                        {{ $this->santriAbsensiStats['hadir'] }} <span class="text-xs font-normal text-zinc-500">Hari</span>
                    </div>
                    <div class="mt-1 text-[11px] text-zinc-400">
                        Izin: {{ $this->santriAbsensiStats['izin'] }} &bull; Sakit: {{ $this->santriAbsensiStats['sakit'] }} &bull; Alpa: {{ $this->santriAbsensiStats['alpa'] }}
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">{{ __('Saldo Tabungan') }}</div>
                    <div class="mt-2 text-xl font-extrabold text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($santri->tabungan_saldo ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-[11px] text-zinc-400">{{ __('Saldo aktif santri') }}</div>
                </div>
            </div>

            {{-- Nilai Akademik Santri (READ ONLY - NO PRINT BUTTON PER USER RULE) --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900 space-y-4">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-3 dark:border-zinc-800">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Nilai Mata Pelajaran (Read-Only)') }}</h3>
                        <p class="text-xs text-zinc-500">{{ __('Daftar perolehan nilai mata pelajaran santri.') }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-zinc-100 text-zinc-600 font-semibold dark:bg-zinc-800 dark:text-zinc-400">
                        {{ __('Mode Lihat Nilai') }}
                    </span>
                </div>

                @if ($this->santriNilaiList->isEmpty())
                    <div class="text-center py-6 text-xs text-zinc-500">
                        {{ __('Belum ada data nilai yang diinputkan untuk santri ini.') }}
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-zinc-200 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    <th class="py-3 px-4 font-bold">{{ __('Semester') }}</th>
                                    <th class="py-3 px-4 font-bold">{{ __('Mata Pelajaran') }}</th>
                                    <th class="py-3 px-4 font-bold text-center">{{ __('Nilai Angka') }}</th>
                                    <th class="py-3 px-4 font-bold text-center">{{ __('Predikat') }}</th>
                                    <th class="py-3 px-4 font-bold">{{ __('Catatan Guru') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach ($this->santriNilaiList as $nilai)
                                    <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition">
                                        <td class="py-3 px-4 font-semibold text-zinc-800 dark:text-zinc-200">
                                            {{ $nilai->semester?->tahunAkademik?->nama }} ({{ ucfirst($nilai->semester?->tipe->value ?? '') }})
                                        </td>
                                        <td class="py-3 px-4 font-bold text-emerald-700 dark:text-emerald-400">
                                            {{ $nilai->mapel?->nama ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-extrabold text-zinc-900 dark:text-white">
                                            {{ $nilai->nilai ?? $nilai->nilai_angka ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded-md font-bold text-[11px] {{ $nilai->predikat?->value === 'A' ? 'bg-emerald-100 text-emerald-800' : ($nilai->predikat?->value === 'B' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $nilai->predikat?->value ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-zinc-500 italic">
                                            {{ $nilai->keterangan ?: ($nilai->catatan ?? '-') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Riwayat Keuangan & Tabungan Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Pembayaran SPP & Haflatul Imtihan --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900 space-y-4">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white border-b border-zinc-100 pb-2 dark:border-zinc-800">
                        {{ __('Riwayat Pembayaran SPP') }}
                    </h3>

                    @if ($this->santriSppList->isEmpty())
                        <p class="text-xs text-zinc-500 py-3">{{ __('Belum ada riwayat pembayaran SPP.') }}</p>
                    @else
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            @foreach ($this->santriSppList as $spp)
                                <div class="p-3 rounded-xl border border-zinc-100 bg-zinc-50 flex items-center justify-between text-xs dark:border-zinc-800 dark:bg-zinc-800/60">
                                    <div>
                                        <span class="font-bold text-zinc-800 dark:text-zinc-200">
                                            Bulan {{ \Carbon\Carbon::create()->month($spp->bulan)->locale('id')->translatedFormat('F') }} {{ $spp->tahun }}
                                        </span>
                                        <span class="block text-[11px] text-zinc-400">Bayar: {{ $spp->created_at?->format('d/m/Y') }}</span>
                                    </div>
                                    <span class="font-extrabold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($spp->nominal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Tabungan Santri --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900 space-y-4">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-white border-b border-zinc-100 pb-2 dark:border-zinc-800">
                        {{ __('Transaksi Tabungan Terbaru') }}
                    </h3>

                    @if ($this->santriTabunganList->isEmpty())
                        <p class="text-xs text-zinc-500 py-3">{{ __('Belum ada mutasi tabungan.') }}</p>
                    @else
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            @foreach ($this->santriTabunganList as $tabungan)
                                <div class="p-3 rounded-xl border border-zinc-100 bg-zinc-50 flex items-center justify-between text-xs dark:border-zinc-800 dark:bg-zinc-800/60">
                                    <div>
                                        <span class="font-bold uppercase {{ $tabungan->tipe === 'setor' ? 'text-emerald-700' : 'text-rose-700' }}">
                                            {{ $tabungan->tipe === 'setor' ? '+ Setor' : '- Tarik' }}
                                        </span>
                                        <span class="block text-[11px] text-zinc-400">{{ $tabungan->tanggal?->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-extrabold text-zinc-900 dark:text-white block">
                                            Rp {{ number_format($tabungan->nominal, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-zinc-400">Saldo: Rp {{ number_format($tabungan->saldo_akhir, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

    @else
        {{-- ================= ADMIN / OPERATOR / GURU DASHBOARD ================= --}}
        {{-- Welcome Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl" class="text-2xl font-bold">{{ __('Dashboard Utama') }}</flux:heading>
                <flux:subheading class="mt-1">
                    {{ __('Selamat datang kembali, :name.', ['name' => auth()->user()->name]) }}
                    @if ($this->activeLembaga)
                        &bull; <span class="font-bold text-emerald-600 dark:text-emerald-400">[{{ $this->activeLembaga->nama }}]</span>
                    @endif
                    @if ($this->semesterAktif)
                        &bull; <span class="font-medium text-emerald-600 dark:text-emerald-400">{{ __('Semester Aktif: :tahun :tipe', ['tahun' => $this->semesterAktif->tahunAkademik->nama, 'tipe' => ucfirst($this->semesterAktif->tipe->value)]) }}</span>
                    @endif
                </flux:subheading>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}
                </span>
            </div>
        </div>

        {{-- Top Key Performance Indicator Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition duration-300 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Santri Aktif') }}</flux:text>
                        <div class="mt-2 flex items-baseline gap-2">
                            <flux:heading size="xl" class="text-3xl! font-extrabold text-zinc-900 dark:text-white">{{ number_format($this->santriAktifCount) }}</flux:heading>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Santri</span>
                        </div>
                    </div>
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <flux:icon name="users" class="size-6" />
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-zinc-400">{{ __('Tercatat aktif pada semester ini') }}</div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition duration-300 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Guru / Pengajar') }}</flux:text>
                        <div class="mt-2 flex items-baseline gap-2">
                            <flux:heading size="xl" class="text-3xl! font-extrabold text-zinc-900 dark:text-white">{{ number_format($this->guruAktifCount) }}</flux:heading>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Ustadz/ah</span>
                        </div>
                    </div>
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <flux:icon name="user-circle" class="size-6" />
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-zinc-400">{{ __('Tenaga pendidik aktif') }}</div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition duration-300 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Total Kelas') }}</flux:text>
                        <div class="mt-2 flex items-baseline gap-2">
                            <flux:heading size="xl" class="text-3xl! font-extrabold text-zinc-900 dark:text-white">{{ number_format($this->kelasCount) }}</flux:heading>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Rombel</span>
                        </div>
                    </div>
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <flux:icon name="rectangle-group" class="size-6" />
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-zinc-400">{{ __('Rombongan belajar aktif') }}</div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition duration-300 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('SPP Bulan Ini') }}</flux:text>
                        <div class="mt-2 flex items-baseline gap-1">
                            <flux:heading size="xl" class="text-2xl! font-extrabold text-zinc-900 dark:text-white">Rp {{ number_format($this->sppBulanIni, 0, ',', '.') }}</flux:heading>
                        </div>
                    </div>
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                        <flux:icon name="banknotes" class="size-6" />
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-zinc-400">{{ __('Total pembayaran masuk bulan ini') }}</div>
            </div>
        </div>

        {{-- Analytics & Charts Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- SPP Trend Line Chart --}}
            <div class="lg:col-span-2 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:heading size="lg" class="font-bold">{{ __('Tren Penerimaan SPP (6 Bulan Terakhir)') }}</flux:heading>
                        <flux:subheading class="text-xs">{{ __('Grafik akumulasi nominal SPP terbayar per bulan') }}</flux:subheading>
                    </div>
                </div>
                <div x-data="sppTrendChartComponent(@js($this->sppTrendChart))" class="w-full">
                    <div x-ref="sppChart" class="w-full h-64"></div>
                </div>
            </div>

            {{-- Absensi Donut Chart --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:heading size="lg" class="font-bold">{{ __('Persentase Kehadiran') }}</flux:heading>
                        <flux:subheading class="text-xs">{{ __('Statistik presensi bulan ini') }}</flux:subheading>
                    </div>
                </div>
                <div x-data="absensiChartComponent(@js($this->absensiChart))" class="w-full">
                    <div x-ref="absensiChart" class="w-full h-64"></div>
                </div>
            </div>
        </div>

        {{-- Santri per Kelas & Recent Table Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Santri per Kelas Bar Chart --}}
            <div class="lg:col-span-2 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <flux:heading size="lg" class="font-bold">{{ __('Jumlah Santri Per Kelas') }}</flux:heading>
                        <flux:subheading class="text-xs">{{ __('Distribusi santri aktif berdasarkan rombel') }}</flux:subheading>
                    </div>
                </div>
                <div x-data="kelasChartComponent(@js($this->santriPerKelasChart))" class="w-full">
                    <div x-ref="kelasChart" class="w-full h-64"></div>
                </div>
            </div>

            {{-- Recent Registered Santris List --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="font-bold">{{ __('Santri Terbaru') }}</flux:heading>
                    <flux:button variant="subtle" size="xs" :href="route('kesantrian.santri')" wire:navigate>{{ __('Lihat Semua') }}</flux:button>
                </div>

                <div class="space-y-3">
                    @forelse ($this->recentSantris as $santri)
                        <div class="flex items-center justify-between rounded-xl border border-zinc-100 bg-zinc-50/50 p-3 dark:border-zinc-800 dark:bg-zinc-800/40">
                            <div class="flex items-center gap-3">
                                <div class="flex size-9 items-center justify-center rounded-xl bg-emerald-500/10 font-bold text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400 text-xs">
                                    {{ Str::substr($santri->nama_lengkap, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-zinc-900 dark:text-white">{{ $santri->nama_lengkap }}</div>
                                    <div class="text-[11px] text-zinc-500">{{ $santri->kelas?->nama ?? '-' }} &bull; {{ $santri->noinduk }}</div>
                                </div>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                {{ ucfirst($santri->status->value) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-zinc-400">{{ __('Belum ada data santri.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <script>
            function sppTrendChartComponent(chartData) {
                return {
                    init() {
                        if (!this.$refs.sppChart) return;
                        const options = {
                            chart: {
                                type: 'area',
                                height: 260,
                                toolbar: { show: false },
                                fontFamily: 'inherit',
                                sparkline: { enabled: false },
                            },
                            series: [{
                                name: 'Penerimaan SPP (Rp)',
                                data: chartData.series || []
                            }],
                            xaxis: {
                                categories: chartData.categories || [],
                                labels: { style: { colors: '#71717a', fontSize: '11px' } }
                            },
                            yaxis: {
                                labels: {
                                    style: { colors: '#71717a', fontSize: '11px' },
                                    formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                                }
                            },
                            colors: ['#059669'],
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.45,
                                    opacityTo: 0.05,
                                    stops: [0, 90, 100]
                                }
                            },
                            stroke: { curve: 'smooth', width: 3 },
                            dataLabels: { enabled: false },
                            grid: { borderColor: '#e4e4e7', strokeDashArray: 4 }
                        };

                        const chart = new ApexCharts(this.$refs.sppChart, options);
                        chart.render();
                    }
                };
            }

            function absensiChartComponent(chartData) {
                return {
                    init() {
                        if (!this.$refs.absensiChart) return;
                        const options = {
                            chart: {
                                type: 'donut',
                                height: 260,
                                fontFamily: 'inherit',
                            },
                            series: chartData.series || [],
                            labels: chartData.labels || [],
                            colors: ['#10b981', '#3b82f6', '#f59e0b', '#f43f5e'],
                            legend: { position: 'bottom', fontSize: '11px' },
                            stroke: { show: false },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                label: 'Total Absen',
                                                color: '#71717a',
                                                fontSize: '12px'
                                            }
                                        }
                                    }
                                }
                            }
                        };

                        const chart = new ApexCharts(this.$refs.absensiChart, options);
                        chart.render();
                    }
                };
            }

            function kelasChartComponent(chartData) {
                return {
                    init() {
                        if (!this.$refs.kelasChart) return;
                        const options = {
                            chart: {
                                type: 'bar',
                                height: 260,
                                toolbar: { show: false },
                                fontFamily: 'inherit',
                            },
                            series: [{
                                name: 'Jumlah Santri',
                                data: chartData.series || []
                            }],
                            xaxis: {
                                categories: chartData.categories || [],
                                labels: { style: { colors: '#71717a', fontSize: '11px' } }
                            },
                            yaxis: {
                                labels: { style: { colors: '#71717a', fontSize: '11px' } }
                            },
                            colors: ['#059669'],
                            plotOptions: {
                                bar: {
                                    borderRadius: 6,
                                    columnWidth: '40%',
                                }
                            },
                            dataLabels: { enabled: true, style: { fontSize: '11px' } },
                            grid: { borderColor: '#e4e4e7', strokeDashArray: 4 }
                        };

                        const chart = new ApexCharts(this.$refs.kelasChart, options);
                        chart.render();
                    }
                };
            }
        </script>
    @endif
</div>
