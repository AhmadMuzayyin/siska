@php
    date_default_timezone_set('Asia/Jakarta');
@endphp
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
    <div class="container mx-auto px-4 py-4 md:py-8">
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6 max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-center mb-2">Absensi Kehadiran Guru</h2>
                <p class="text-gray-600 text-center text-sm md:text-base">
                    {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                </p>
            </div>

            <!-- Status Buttons Section -->
            <div class="status-buttons mb-6">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                    <button type="button" onclick="setAllStatus('hadir')"
                        class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                        Hadir Semua
                    </button>
                    <button type="button" onclick="setAllStatus('izin')"
                        class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                        Izin Semua
                    </button>
                </div>
            </div>

            <!-- Absensi Table -->
            <div class="responsive-table mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($teachers as $teacher)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $teacher->user->name }}
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex justify-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status_{{ $teacher->id }}" value="hadir"
                                                class="custom-radio radio-hadir">
                                            <span class="ml-1 text-sm text-gray-600">H</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="status_{{ $teacher->id }}" value="izin"
                                                class="custom-radio radio-izin" checked>
                                            <span class="ml-1 text-sm text-gray-600">I</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" id="saveAbsensi"
                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    Simpan Absensi
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
            $('#saveAbsensi').click(function() {
                const absensiData = [];

                document.querySelectorAll('tbody tr').forEach(row => {
                    const guru_id = row.querySelector('input[type="radio"]').name.replace('status_',
                        '');
                    const status = row.querySelector('input[type="radio"]:checked').value;

                    absensiData.push({
                        guru_id: guru_id,
                        status: status
                    });
                });

                $.ajax({
                    url: "{{ route('absensiguru.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        absensi: absensiData
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ url('/admin/absensi-gurus') }}";
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error ||
                            'Terjadi kesalahan saat menyimpan absensi');
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
