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
            <h5 class="m-0  text-center" style="font-size: 1.1rem;">
                JADWAL PELAJARAN
            </h5>
            <h5 class="m-0 text-center" style="font-size: 1rem;">MADRASAH DINIYAH TAKMILIYAH AWWALIYAH (MDTA)</h5>
            <h6 class="m-0 fw-bold text-center" style="font-size: 1rem;">
                {{ $title ?? '' }} {{ $tahunAkademik?->nama }}
            </h6>
        </div>
    </div>
    @php
        $kelas_id = [];
    @endphp
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle" style="font-size: 0.9rem; width: 8%;">No</th>
                <th rowspan="2" class="text-center align-middle" style="font-size: 0.9rem; width: 8%;">Hari</th>
                <th colspan="12" class="text-center text-uppercase" style="font-size: 0.9rem;">Kelas & Wali Kelas
                </th>
            </tr>
            <tr>
                @foreach ($kelas as $kelas)
                    @php
                        $kelas_id[] = $kelas->id;
                    @endphp
                    <td class="text-center text-uppercase" style="font-size: 0.85rem; width: 7%;">
                        <span class="d-block fw-bold">{{ $kelas->nama }}</span>
                        <span class="d-block text-muted">
                            {{ $kelas->waliKelas?->guru->gender == 'Laki-laki' ? 'Ustadz' : 'Ustadzah' }}
                            {{ $kelas->waliKelas?->guru->user->name }}
                        </span>
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $hari = ['Sabtu', 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis'];
            @endphp
            @foreach ($hari as $index => $h)
                <tr>
                    <td class="text-center" style="font-size: 0.85rem;">{{ $index + 1 }}</td>
                    <td style="font-size: 0.85rem;">{{ $h }}</td>
                    @foreach ($jadwalPelajaran as $item)
                        @if (in_array($item->kelas_id, $kelas_id) && $item->hari == $h)
                            <th class="text-center text-uppercase" style="font-size: 0.85rem;">
                                <span class="d-block">{{ $item->mapel->nama }}</span>
                                <span class="d-block text-muted fw-normal">{{ $item->guru->user->name }}</span>
                            </th>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 1000);
    </script> --}}
</body>

</html>
