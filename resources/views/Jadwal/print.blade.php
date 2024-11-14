<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        @page {
            size: legal landscape;
            /* F4 is close to US Legal size */
            margin: 1cm;
        }

        body {
            width: 100%;
            height: 100%;
        }

        @media print {
            body {
                width: 330.2mm;
                /* F4 height becomes width in landscape */
                height: 215.9mm;
                /* F4 width becomes height in landscape */
            }
        }
    </style>
    <title>Print Jadwal Pelajaran</title>
</head>


<body class="p-2">
    <div class="d-flex align-items-center mb-4 position-relative">
        <img src="{{ asset($logo) }}" alt="Logo Sekolah"
            style="height: 120px; width: auto; position: absolute; left: 50px;">
        <div class="text-center flex-grow-1 mx-auto" style="max-width: 800px;">
            <h5 class="m-0 font-weight-bold text-center" style="font-size: 1.1rem;">JADWAL PELAJARAN {{ $title ?? '' }}
            </h5>
            <h6 class="m-0 font-weight-bold text-center" style="font-size: 1rem;">TAHUN PELAJARAN
                {{ $tahunAkademik?->nama }}</h6>
            <p class="m-0 font-weight-bold text-center" style="font-size: 0.9rem;">
                NSM : {{ $nsm ?? '' }}
            </p>
            <p class="m-0 font-weight-bold text-center" style="font-size: 0.9rem;">
                {{ $alamat ?? '' }}
                <br>
                Telp. {{ $telepon ?? '' }} {{ $email ?? '' }} {{ env('APP_URL') }}
            </p>
        </div>
    </div>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle" style="font-size: 0.9rem; width: 8%;">No</th>
                <th rowspan="2" class="text-center align-middle" style="font-size: 0.9rem; width: 8%;">Kelas</th>
                <th colspan="12" class="text-center" style="font-size: 0.9rem;">Mata Pelajaran</th>
            </tr>
            <tr>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Senin</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Selasa</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Rabu</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Kamis</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Sabtu</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
                <td class="text-center" style="font-size: 0.85rem; width: 7%;">Minggu</td>
                <td style="font-size: 0.85rem; width: 10%;">Guru</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($jadwalPelajaran->groupBy('kelas.nama') as $kelas => $jadwals)
                <tr>
                    <td class="text-center" style="font-size: 0.85rem;">{{ $loop->iteration }}</td>
                    <td style="font-size: 0.85rem;">{{ $kelas }}</td>

                    @php
                        $seninJadwal = $jadwals->where('hari', 'Senin')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $seninJadwal ? $seninJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $seninJadwal ? $seninJadwal->guru->user->name : '' }}</td>

                    @php
                        $selasaJadwal = $jadwals->where('hari', 'Selasa')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $selasaJadwal ? $selasaJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $selasaJadwal ? $selasaJadwal->guru->user->name : '' }}</td>

                    @php
                        $rabuJadwal = $jadwals->where('hari', 'Rabu')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $rabuJadwal ? $rabuJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $rabuJadwal ? $rabuJadwal->guru->user->name : '' }}</td>

                    @php
                        $kamisJadwal = $jadwals->where('hari', 'Kamis')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $kamisJadwal ? $kamisJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $kamisJadwal ? $kamisJadwal->guru->user->name : '' }}</td>

                    @php
                        $sabtuJadwal = $jadwals->where('hari', 'Sabtu')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $sabtuJadwal ? $sabtuJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $sabtuJadwal ? $sabtuJadwal->guru->user->name : '' }}</td>

                    @php
                        $mingguJadwal = $jadwals->where('hari', 'Minggu')->first();
                    @endphp
                    <td style="font-size: 0.85rem;">{{ $mingguJadwal ? $mingguJadwal->mapel->nama : '' }}</td>
                    <td style="font-size: 0.85rem;">{{ $mingguJadwal ? $mingguJadwal->guru->user->name : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 1000);
    </script>
</body>

</html>
