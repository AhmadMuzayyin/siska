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
            margin: 1cm;
        }

        body {
            width: 100%;
            height: 100%;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem;
        }

        .waktu-cell {
            width: 8%;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .kelas-cell {
            width: 8%;
            vertical-align: middle;
            font-size: 0.9rem;
            text-align: center;
        }

        .jadwal-cell {
            font-size: 0.85rem;
            text-align: center;
        }

        @media print {
            body {
                width: 330.2mm;
                height: 215.9mm;
            }
        }
    </style>
    <title>Jadwal Kegiatan Santri</title>
</head>

<body class="p-2">
    <div class="text-center mb-4">
        <h5 class="m-0" style="font-size: 1.1rem;">JADWAL KEGIATAN SANTRI</h5>
        <h5 class="m-0" style="font-size: 1rem;">MADRASAH DINIYAH TAKMILIYA AWWALIYAH</h5>
        <h6 class="m-0 fw-bold" style="font-size: 1rem;">MADRASATUL QUR'AN AL-AMIN</h6>
    </div>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th class="text-center waktu-cell">Waktu</th>
                <th class="text-center kelas-cell">Kelas</th>
                @foreach (['Sabtu', 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis'] as $hari)
                    <th class="text-center" style="font-size: 0.9rem;">{{ $hari }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Blok Waktu 14.54 - 15.40 -->
            <tr>
                <td rowspan="1" class="waktu-cell text-center">14.54 - 15.40</td>
                <td rowspan="1" class="kelas-cell">Semua Santri</td>
                @for ($i = 0; $i < 6; $i++)
                    <td class="jadwal-cell">
                        <div>Rotibul Haddad</div>
                        <div>Fashohah</div>
                        <div>Gharib</div>
                    </td>
                @endfor
            </tr>

            <!-- Blok Waktu 15.40 - 16.40 -->
            <tr>
                <td rowspan="1" class="waktu-cell text-center">15.40 - 16.40</td>
                <td rowspan="1" class="kelas-cell">Semua Santri</td>
                @for ($i = 0; $i < 6; $i++)
                    <td class="jadwal-cell">
                        <div>Suara & Lagu</div>
                        <div>Teori Ilmu Tajwid</div>
                        <div>Khotthul Jamil</div>
                    </td>
                @endfor
            </tr>

            <!-- Blok Waktu 17.10 - 18.10 -->
            <tr>
                <td rowspan="6" class="waktu-cell text-center">17.10 - 18.10</td>
                {{-- <td class="kelas-cell">TPQ</td>
                @foreach ($jadwalPelajaran as $jadwal)
                    @if ($jadwal->kelas_id === 'TPQ')
                        <td class="jadwal-cell">
                            <div>{{ $jadwal->mapel->nama }}</div>
                            <div class="text-muted">{{ $jadwal->guru->user->name }}</div>
                        </td>
                    @endif
                @endforeach --}}
            </tr>
            @foreach (['Shifir', '1 MDTA', '2 MDTA', '3 MDTA', '4 MDTA'] as $kelasNama)
                <tr>
                    <td class="kelas-cell">{{ $kelasNama }}</td>
                    @foreach ($jadwalPelajaran as $jadwal)
                        @if ($jadwal->kelas->nama == $kelasNama)
                            <td class="jadwal-cell">
                                <div>{{ $jadwal->mapel->nama }}</div>
                                {{-- <div class="text-muted">{{ $jadwal->guru->user->name }}</div> --}}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach

            <!-- Blok Waktu 18.10 - 19.00 -->
            <tr>
                <td rowspan="1" class="waktu-cell text-center">18.10 - 19.00</td>
                <td rowspan="1" class="kelas-cell">Semua Santri</td>
                @for ($i = 0; $i < 6; $i++)
                    <td class="jadwal-cell">
                        <div>Praktek Sholat</div>
                        <div>Juz 30</div>
                        <div>Juz 29</div>
                        <div>Juz 1</div>
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>
</body>

</html>
