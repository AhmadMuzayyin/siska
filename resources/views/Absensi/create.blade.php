<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Santri</title>
    @csrf
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    body {
        background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
        background-size: 200% 200%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    /* Custom Radio Button Styles */
    .custom-radio {
        @apply w-5 h-5 cursor-pointer transition-all duration-200 ease-in-out;
    }

    .radio-hadir:checked {
        @apply accent-green-500;
    }

    .radio-sakit:checked {
        @apply accent-blue-500;
    }

    .radio-izin:checked {
        @apply accent-yellow-500;
    }

    .radio-alpha:checked {
        @apply accent-red-500;
    }

    /* Responsive Table */
    @media (max-width: 640px) {
        .responsive-table {
            @apply block w-full overflow-x-auto whitespace-nowrap;
        }

        .status-buttons {
            @apply grid grid-cols-2 gap-2;
        }
    }
</style>

<body class="min-h-screen">
    @php
        $jadwalId = cache('jadwal_pelajaran_aktif');
        $santris = collect();
        $absensi = collect();

        if ($jadwalId) {
            $jadwal = App\Models\JadwalPelajaran::with('kelas.santri')->find($jadwalId);
            if ($jadwal && $jadwal->kelas) {
                $santris = $jadwal->kelas->santri;
                $absensi = App\Models\Absensi::where('jadwal_pelajaran_id', $jadwalId)
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->get();
            }
        }
        if ($absensi->isNotEmpty()) {
            $absensi->load('santri');
        }
        // dd($santris, $absensi);
    @endphp
    @if (($jadwalId && $santris->isNotEmpty()) || $absensi->isNotEmpty())
        <div class="container mx-auto px-4 py-4 md:py-8">
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-center mb-2">Absensi Kehadiran Santri</h2>
                    <p class="text-gray-600 text-center text-sm md:text-base">
                        {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                    </p>
                </div>

                <!-- Status Buttons Section -->
                <div class="status-buttons mb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <button type="button" onclick="setAllStatus('hadir')"
                            class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Hadir Semua
                        </button>
                        <button type="button" onclick="setAllStatus('sakit')"
                            class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sakit Semua
                        </button>
                        <button type="button" onclick="setAllStatus('izin')"
                            class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Izin Semua
                        </button>
                        <button type="button" onclick="setAllStatus('alpha')"
                            class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Alpha Semua
                        </button>
                    </div>
                </div>

                <!-- Absensi Table -->
                <div class="responsive-table mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Santri</th>
                                <th
                                    class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($absensi->isNotEmpty())
                                @foreach ($absensi as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->santri->nama_lengkap }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="flex justify-center space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $item->santri->noinduk }}"
                                                        value="hadir" class="custom-radio radio-hadir"
                                                        {{ $item->status == 'Hadir' ? 'checked' : '' }}>
                                                    <span class="ml-1 text-sm text-gray-600">H</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $item->santri->noinduk }}"
                                                        value="sakit" class="custom-radio radio-sakit"
                                                        {{ $item->status == 'Sakit' ? 'checked' : '' }}>
                                                    <span class="ml-1 text-sm text-gray-600">S</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $item->santri->noinduk }}"
                                                        value="izin" class="custom-radio radio-izin"
                                                        {{ $item->status == 'Izin' ? 'checked' : '' }}>
                                                    <span class="ml-1 text-sm text-gray-600">I</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $item->santri->noinduk }}"
                                                        value="alpha" class="custom-radio radio-alpha"
                                                        {{ $item->status == 'Alpha' ? 'checked' : '' }}>
                                                    <span class="ml-1 text-sm text-gray-600">A</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($santris as $santri)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $santri->nama_lengkap }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="flex justify-center space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $santri->noinduk }}"
                                                        value="hadir" class="custom-radio radio-hadir" checked>
                                                    <span class="ml-1 text-sm text-gray-600">H</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $santri->noinduk }}"
                                                        value="sakit" class="custom-radio radio-sakit">
                                                    <span class="ml-1 text-sm text-gray-600">S</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $santri->noinduk }}"
                                                        value="izin" class="custom-radio radio-izin">
                                                    <span class="ml-1 text-sm text-gray-600">I</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="status_{{ $santri->noinduk }}"
                                                        value="alpha" class="custom-radio radio-alpha">
                                                    <span class="ml-1 text-sm text-gray-600">A</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="saveAbsensi"
                        class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan Absensi
                    </button>
                    <button type="button" id="selesaiAbsensi"
                        class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Selesai
                    </button>
                </div>
            </div>
        </div>

        <!-- Session Selection -->
    @else
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
                <h2 class="text-xl font-bold mb-4 text-center">Pilih Jadwal Pelajaran</h2>

                <div class="mb-6">
                    <select id="jadwal_pelajaran"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                        @foreach ($jadwalPelajaran as $item)
                            @if (auth()->user()->role == 'guru')
                                @if (auth()->user()->id == $item->guru->user_id)
                                    <option value="{{ $item->id }}">
                                        {{ $item->kelas->nama }} - {{ $item->mapel->nama }} -
                                        {{ $item->jam_mulai }} - {{ $item->jam_selesai }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ $item->id }}">
                                    {{ $item->kelas->nama }} - {{ $item->mapel->nama }} -
                                    {{ $item->guru->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <a href="{{ url('/admin/absensis') }}"
                    class="block w-full bg-gray-600 text-white py-3 px-4 rounded-lg text-center hover:bg-gray-700 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function setAllStatus(status) {
            document.querySelectorAll(`input[value="${status}"]`).forEach(radio => {
                radio.checked = true;
            });
        }

        $(document).ready(function() {
            // Save Absensi
            $('#saveAbsensi').click(function() {
                const absensiData = [];

                document.querySelectorAll('tbody tr').forEach(row => {
                    const noinduk = row.querySelector('input[type="radio"]').name.replace('status_',
                        '');
                    const status = row.querySelector('input[type="radio"]:checked').value;

                    absensiData.push({
                        noinduk: noinduk,
                        status: status
                    });
                });

                $.ajax({
                    url: "{{ route('absensi.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        absensi: absensiData
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error ||
                            'Terjadi kesalahan saat menyimpan absensi');
                    }
                });
            });

            // Finish Absensi
            $('#selesaiAbsensi').click(function() {
                $.ajax({
                    url: "{{ route('absensi.forget') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        window.location.href = "{{ url('/admin/absensis') }}";
                    }
                });
            });

            // Handle Jadwal Selection
            $('#jadwal_pelajaran').change(function() {
                const jadwal_id = $(this).val();
                $(this).prop('disabled', true);

                $.ajax({
                    url: "{{ route('absensi.session') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        jadwal_pelajaran: jadwal_id
                    },
                    success: function() {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat memilih jadwal');
                        $(this).prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>

</html>
