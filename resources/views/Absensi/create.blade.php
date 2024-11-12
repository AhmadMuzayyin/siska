<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Absensi</title>
    @csrf
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    body {
        background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
        background-image:
            radial-gradient(circle at 10% 20%, rgba(255, 107, 107, 0.8) 0%, transparent 40%),
            radial-gradient(circle at 90% 50%, rgba(78, 205, 196, 0.8) 0%, transparent 40%),
            radial-gradient(circle at 50% 80%, rgba(255, 209, 102, 0.8) 0%, transparent 40%);
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
</style>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <x-preload />
    @if (session('has_ready'))
        <div class="w-full max-w-md p-6 bg-white shadow-md rounded-lg">
            <h2 class="text-2xl font-bold mb-4 text-center">Absensi Kehadiran Santri</h2>
            <!-- Absensi Form -->
            <form class="mb-6">
                <div class="mb-4">
                    <label for="noinduk" class="block text-gray-700">No. Induk</label>
                    <input type="text" id="noinduk" placeholder="Masukkan No. Induk"
                        class="mt-1 p-2 w-full border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 p-2 w-full border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="hadir">Hadir</option>
                        <option value="tidak hadir">Tidak Hadir</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>
                <button type="button" class="w-full bg-green-600 text-white p-2 rounded-md hover:bg-green-500"
                    id="save">Simpan</button>
                <div class="flex gap-2 w-full">
                    <button type="button"
                        class="w-1/2 bg-sky-500 text-white p-2 rounded-md hover:bg-sky-600 my-2 flex items-center justify-center"
                        id="scan">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5 8a1 1 0 0 1-2 0V5.923c0-.76.082-1.185.319-1.627.223-.419.558-.754.977-.977C4.738 3.082 5.162 3 5.923 3H8a1 1 0 0 1 0 2H5.923c-.459 0-.57.022-.684.082a.364.364 0 0 0-.157.157c-.06.113-.082.225-.082.684V8zm3 11a1 1 0 1 1 0 2H5.923c-.76 0-1.185-.082-1.627-.319a2.363 2.363 0 0 1-.977-.977C3.082 19.262 3 18.838 3 18.077V16a1 1 0 1 1 2 0v2.077c0 .459.022.57.082.684.038.07.087.12.157.157.113.06.225.082.684.082H8zm7-15a1 1 0 0 0 1 1h2.077c.459 0 .57.022.684.082.07.038.12.087.157.157.06.113.082.225.082.684V8a1 1 0 1 0 2 0V5.923c0-.76-.082-1.185-.319-1.627a2.363 2.363 0 0 0-.977-.977C19.262 3.082 18.838 3 18.077 3H16a1 1 0 0 0-1 1zm4 12a1 1 0 1 1 2 0v2.077c0 .76-.082 1.185-.319 1.627a2.364 2.364 0 0 1-.977.977c-.442.237-.866.319-1.627.319H16a1 1 0 1 1 0-2h2.077c.459 0 .57-.022.684-.082a.363.363 0 0 0 .157-.157c.06-.113.082-.225.082-.684V16zM3 11a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2H3z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg>
                        Scan QR Code
                    </button>
                    <button type="button"
                        class="w-1/2 bg-teal-500 text-white p-2 rounded-md hover:bg-teal-600 my-2 flex items-center justify-center"
                        id="selesai">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg>
                        Selesai
                    </button>
                </div>
            </form>
            <!-- Daftar Absensi -->
            <h3 class="text-xl font-semibold mb-2">Daftar Absensi</h3>
            <table class="w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-2 border-b">No</th>
                        <th class="p-2 border-b">Nama</th>
                        <th class="p-2 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $kehadiran)
                        <tr class="text-center">
                            <td class="p-2 border-b">{{ $loop->iteration }}</td>
                            <td class="p-2 border-b">{{ $kehadiran->santri->nama_lengkap }}</td>
                            <td
                                class="p-2 border-b text-{{ $kehadiran->status == 'Hadir' ? 'green' : ($kehadiran->status == 'Izin' ? 'yellow' : 'red') }}-600">
                                {{ $kehadiran->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $absensi->links() }}
        </div>
        <x-modal title="Scan QR Code" id="cameraModal" description="Silahkan scan QR Code yang ada di kartu santri">
            <div id="video" class="w-full h-75 bg-gray-200 rounded-md"></div>
        </x-modal>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                // scan absensi
                let scanner = new Html5QrcodeScanner(
                    "video", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    /* verbose= */
                    false);

                function startScanner() {
                    scanner.render(onScanSuccess, onScanFailure);
                }
                $('#scan').click(function() {
                    $('#cameraModal').removeClass('hidden').css('opacity', '0');
                    setTimeout(() => {
                        $('#cameraModal').css({
                            'opacity': '1',
                            'transition': 'opacity 500ms ease-in-out'
                        });
                    }, 10);
                    // get access permission camera
                    navigator.mediaDevices.getUserMedia({
                            video: true
                        })
                        .then(function(stream) {
                            startScanner()
                        })
                        .catch(function(err) {
                            console.log(err);
                        });
                });

                function onScanSuccess(decodedText, decodedResult) {
                    // $('#noinduk').val(decodedText);
                    // $('#cameraModal').addClass('hidden');
                    // html5QrcodeScanner.stop();
                    // cek ada di database
                    $.ajax({
                        url: "{{ route('absensi.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            noinduk: decodedText
                        },
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseJSON.error);
                        }
                    });
                }

                function onScanFailure(error) {
                    // console.warn(`Code scan error = ${error}`);
                }
                // close camera
                $('#closeModalBtn').click(function() {
                    $('#cameraModal').css({
                        'opacity': '0',
                        'transition': 'opacity 500ms ease-in-out'
                    });
                    setTimeout(() => {
                        $('#cameraModal').addClass('hidden');
                    }, 300);
                    const stream = document.querySelector('video').srcObject;
                    const tracks = stream.getTracks();
                    tracks.forEach(track => track.stop());
                    return false;
                });
                // submit absensi
                $('#save').click(function() {
                    var noinduk = $('#noinduk').val();
                    var status = $('#status').val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('absensi.store') }}",
                        method: "POST",
                        data: {
                            _token: _token,
                            noinduk: noinduk,
                            status: status
                        },
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseJSON.error);
                        }
                    });
                });
                // selesai absensi
                $('#selesai').click(function() {
                    $.ajax({
                        url: "{{ route('absensi.forget') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            window.location.href = "{{ url('/admin/absensis') }}";
                        }
                    });
                });
            });
        </script>
    @else
        <div class="w-full max-w-md p-6 bg-white shadow-md rounded-lg">
            <div class="mb-4">
                <label for="jadwal_pelajaran" class="block text-gray-700">Mata Pelajaran Hari Ini</label>
                <select name="jadwal_pelajaran" id="jadwal_pelajaran"
                    class="mt-1 p-2 w-full border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
                                {{ $item->kelas->nama }} - {{ $item->mapel->nama }} - {{ $item->guru->user->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <a href="{{ url('/admin/absensis') }}"
                class="w-full bg-gray-600 text-white p-2 rounded-md hover:bg-gray-500 flex items-center justify-center">
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19L8 12L15 5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
                Batal
            </a>
            {{-- <div class="mb-4">
                <label for="mapel" class="block text-gray-700">Mata Pelajaran</label>
                @foreach ($mapel as $item)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="mapel" id="mapel" value="{{ $item->id }}">
                        <label for="mapel">{{ $item->nama }}</label>
                    </div>
                @endforeach
            </div> --}}
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#jadwal_pelajaran').change(function() {
                    var jadwal_pelajaran = $(this).val();
                    $.ajax({
                        url: "{{ route('absensi.session') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            jadwal_pelajaran: jadwal_pelajaran
                        },
                        success: function(response) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endif
</body>

</html>
