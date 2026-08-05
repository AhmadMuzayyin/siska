<div class="flex flex-col gap-6">
    {{-- ApexCharts CDN --}}
    @assets
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endassets

    {{-- Welcome Header --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="text-2xl font-bold">{{ __('Dashboard Utama') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Selamat datang kembali, :name.', ['name' => auth()->user()->name]) }}
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
                    <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Rombongan Belajar') }}</flux:text>
                    <div class="mt-2 flex items-baseline gap-2">
                        <flux:heading size="xl" class="text-3xl! font-extrabold text-zinc-900 dark:text-white">{{ number_format($this->kelasCount) }}</flux:heading>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Kelas</span>
                    </div>
                </div>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                    <flux:icon name="rectangle-group" class="size-6" />
                </div>
            </div>
            <div class="mt-3 text-[11px] text-zinc-400">{{ __('Total kelas terdaftar') }}</div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition duration-300 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text class="text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('SPP Terkumpul Bulan Ini') }}</flux:text>
                    <div class="mt-2 flex items-baseline gap-1">
                        <flux:heading size="xl" class="text-2xl! font-extrabold text-emerald-600 dark:text-emerald-400">
                            Rp{{ number_format($this->sppBulanIni, 0, ',', '.') }}
                        </flux:heading>
                    </div>
                </div>
                <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-400">
                    <flux:icon name="banknotes" class="size-6" />
                </div>
            </div>
            <div class="mt-3 text-[11px] text-zinc-400">{{ __('Penerimaan pembayaran SPP') }}</div>
        </div>
    </div>

    @if ($this->pendingRegistrations > 0)
        <div class="flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50/80 p-4 dark:border-amber-900/50 dark:bg-amber-950/40">
            <div class="flex items-center gap-3">
                <span class="flex size-3 rounded-full bg-amber-500 animate-ping"></span>
                <flux:text class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                    {{ __(':count pendaftaran santri baru menunggu persetujuan Anda.', ['count' => $this->pendingRegistrations]) }}
                </flux:text>
            </div>
            <flux:button size="sm" variant="primary" class="bg-amber-600! hover:bg-amber-700! text-white!" :href="route('kesantrian.santri')" wire:navigate>
                {{ __('Tinjau Pendaftaran') }}
            </flux:button>
        </div>
    @endif

    {{-- ApexCharts Interactive Visualizations Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        {{-- Chart 1: Tren SPP 6 Bulan (Area Chart) --}}
        <div class="lg:col-span-8 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Tren Pemasukan SPP (6 Bulan Terakhir)') }}</h3>
                    <p class="text-xs text-zinc-500">{{ __('Grafik akumulasi penerimaan dana SPP santri') }}</p>
                </div>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    {{ __('Keuangan') }}
                </span>
            </div>

            <div x-data="sppChartComponent({{ json_encode($this->sppTrendChart) }})" class="mt-4">
                <div x-ref="sppChart"></div>
            </div>
        </div>

        {{-- Chart 2: Donut Absensi --}}
        <div class="lg:col-span-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                    <div>
                        <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Persentase Kehadiran') }}</h3>
                        <p class="text-xs text-zinc-500">{{ __('Rekap absensi bulan ini') }}</p>
                    </div>
                </div>

                <div x-data="absensiChartComponent({{ json_encode($this->absensiChart) }})" class="mt-4 flex justify-center">
                    <div x-ref="absensiChart" class="w-full"></div>
                </div>
            </div>
        </div>

        {{-- Chart 3: Distribusi Santri Per Kelas (Column Chart) --}}
        <div class="lg:col-span-12 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Distribusi Santri Per Kelas') }}</h3>
                    <p class="text-xs text-zinc-500">{{ __('Perbandingan jumlah santri aktif per rombongan belajar') }}</p>
                </div>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                    {{ __('Akademik') }}
                </span>
            </div>

            <div x-data="kelasChartComponent({{ json_encode($this->santriPerKelasChart) }})" class="mt-4">
                <div x-ref="kelasChart"></div>
            </div>
        </div>

    </div>

    {{-- Bottom Section: Recent Registrations & Recent Messages --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Recent Santri Table --}}
        <div class="lg:col-span-7 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Santri Terbaru Mendaftar') }}</h3>
                <a href="{{ route('kesantrian.santri') }}" wire:navigate class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                    {{ __('Lihat Semua →') }}
                </a>
            </div>

            @if ($this->recentSantris->isEmpty())
                <p class="py-8 text-center text-xs text-zinc-400">{{ __('Belum ada data santri mendaftar.') }}</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-100 text-zinc-400 dark:border-zinc-800">
                                <th class="pb-2 font-semibold">{{ __('Nama Santri') }}</th>
                                <th class="pb-2 font-semibold">{{ __('NIS') }}</th>
                                <th class="pb-2 font-semibold">{{ __('Kelas') }}</th>
                                <th class="pb-2 font-semibold">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($this->recentSantris as $santri)
                                <tr>
                                    <td class="py-3 font-semibold text-zinc-900 dark:text-white">{{ $santri->nama_lengkap }}</td>
                                    <td class="py-3 text-zinc-500">{{ $santri->nis }}</td>
                                    <td class="py-3 text-zinc-500">{{ $santri->kelas?->nama ?? '-' }}</td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold {{ $santri->status->value === 'aktif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' }}">
                                            {{ ucfirst($santri->status->value) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Recent Messages List --}}
        <div class="lg:col-span-5 rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                <h3 class="font-bold text-zinc-900 dark:text-white text-base">{{ __('Pesan Masuk Terbaru') }}</h3>
                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <a href="{{ route('konten.pesan') }}" wire:navigate class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                        {{ __('Lihat Semua →') }}
                    </a>
                @endif
            </div>

            @if ($this->recentContacts->isEmpty())
                <p class="py-8 text-center text-xs text-zinc-400">{{ __('Belum ada pesan masuk.') }}</p>
            @else
                <div class="mt-4 flex flex-col divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->recentContacts as $contact)
                        <div class="flex items-center justify-between py-3">
                            <div class="space-y-0.5">
                                <flux:text class="font-semibold text-zinc-900 dark:text-white text-xs">{{ $contact->subject }}</flux:text>
                                <flux:text class="block text-[11px] text-zinc-500">{{ $contact->name }} &bull; {{ $contact->email }}</flux:text>
                            </div>
                            <flux:text class="text-[11px] text-zinc-400 shrink-0">{{ $contact->created_at->diffForHumans() }}</flux:text>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ApexCharts Script Handlers --}}
    <script>
        function sppChartComponent(chartData) {
            return {
                init() {
                    if (!this.$refs.sppChart) return;
                    const options = {
                        chart: {
                            type: 'area',
                            height: 280,
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                        },
                        series: [{
                            name: 'Pemasukan SPP (Rp)',
                            data: chartData.series || []
                        }],
                        xaxis: {
                            categories: chartData.categories || [],
                            labels: { style: { colors: '#71717a', fontSize: '11px' } }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#71717a', fontSize: '11px' },
                                formatter: function (val) {
                                    return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                }
                            }
                        },
                        colors: ['#059669'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        stroke: { curve: 'smooth', width: 3 },
                        dataLabels: { enabled: false },
                        grid: { borderColor: '#e4e4e7', strokeDashArray: 4 },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                                }
                            }
                        }
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
</div>
