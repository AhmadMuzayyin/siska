<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Guru</title>
    @csrf
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    body {
        background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
        background-size: 200% 200%;
        animation: gradientBG 15s ease infinite;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
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
    <div class="container mx-auto px-4 py-4 md:py-12">
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-4 max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-center mb-2">Laporan Absensi Kehadiran Guru</h2>
                <p class="text-gray-600 text-center text-sm md:text-base">
                    {{ \Carbon\Carbon::create($tahun, $bulan)->format('F Y') }}
                </p>
            </div>

            <!-- Filter Section -->
            <form method="GET" action="{{ route('absensiguru.report') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        @php
                        $tanggal = request('tanggal', 'all');
                        @endphp
                        <select name="tanggal" id="tanggal"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all" {{ $tanggal == 'all' ? 'selected' : '' }}>Semua Tanggal</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ $tanggal == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Absensi Table -->
            <div class="responsive-table mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bisyaroh</th>
                            <th
                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kehadiran</th>
                            <th
                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (!$gajiGuru->isNotEmpty())
                            @foreach ($absensiGurus as $absen)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <input type="hidden" name="guru_id"
                                                value="{{ $absen->guru->id }}">
                                            {{ $absen->guru->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <input type="number" name="gaji_guru"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded-md w-full" required>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            <input type="hidden" name="jumlah_hadir"
                                                value="{{ $absen->jumlah_hadir }}">
                                            {{ $absen->jumlah_hadir }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            <span id="jml-gaji">0</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($gajiGuru as $gaji)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                            <input type="hidden" name="guru_id"
                                                value="{{ $gaji->guru->id }}">
                                        <div class="text-sm font-medium text-gray-900">{{ $gaji->guru->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <input type="number" name="gaji_guru" value="{{ $gaji->bisyaroh }}"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded-md w-full" required>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            <input type="hidden" name="jumlah_hadir"
                                                value="{{ $gaji->jumlah_hadir }}">
                                            {{ $gaji->jumlah_hadir }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            <span id="jml-gaji">{{ $gaji->total_gaji }}</span>
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
                <button type="button" id="saveGajiGuru"
                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    Simpan
                </button>
                <button type="button" id="exportAbsensi"
                    class="flex-1 bg-violet-600 text-white py-2 px-4 rounded-lg hover:bg-violet-700 transition-colors flex items-center justify-center gap-2">
                    Export
                </button>
                <button type="button" id="selesaiAbsensi"
                    class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                    Selesai
                </button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function setAllStatus(status) {
            document.querySelectorAll(`input[value="${status}"]`).forEach(radio => {
                radio.checked = true;
            });
        }

        $(document).ready(function() {
            let typingTimer;
            const doneTypingInterval = 500;

            $('input[name^="gaji_"]').on('input', function() {
                clearTimeout(typingTimer);
                const input = $(this);

                typingTimer = setTimeout(function() {
                    const row = input.closest('tr');
                    const gaji = parseFloat(input.val()) || 0;
                    const jumlahHadirInput = row.find('input[name^="jumlah_hadir"]');
                    const jumlahHadir = parseFloat(jumlahHadirInput.val()) || 0;
                    const jumlahSpan = row.find('span[id^="jml-"]');

                    const gajiPerHari = gaji / 26;
                    const totalGaji = gajiPerHari * jumlahHadir;

                    jumlahSpan.text(Math.round(totalGaji));
                }, doneTypingInterval);
            });

            $('#saveGajiGuru').click(function() {
                const gajiData = [];

                document.querySelectorAll('tbody tr').forEach(row => {
                    const guruIdInput = row.querySelector('input[type="hidden"][name^="guru_id"]');
                    const gajiInput = row.querySelector('input[type="number"][name^="gaji_guru"]');
                    const jumlahHadirInput = row.querySelector(
                        'input[type="hidden"][name^="jumlah_hadir"]');
                    const jumlahSpan = row.querySelector('span[id^="jml-gaji"]');

                    if (guruIdInput && gajiInput && jumlahHadirInput && jumlahSpan) {
                        gajiData.push({
                            guru_id: guruIdInput.value,
                            bisyaroh: gajiInput.value,
                            jumlah_hadir: jumlahHadirInput.value,
                            jumlah: jumlahSpan.textContent
                        });
                    }
                });

                $.ajax({
                    url: "{{ route('gajiguru.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: {{ $bulan }},
                        tahun: {{ $tahun }},
                        gaji: gajiData
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error ||
                            'Terjadi kesalahan saat menyimpan data gaji');
                    }
                });
            });
            $('#selesaiAbsensi').click(function() {
                window.location.href = "{{ url('/admin/absensi-gurus') }}";
            });
        });
    </script>
</body>

</html>
