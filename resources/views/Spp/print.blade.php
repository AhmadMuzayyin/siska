@php
    $bulan = [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <title>Rekap SPP {{ date('Y') }}</title>
</head>

<body class="p-2">
    <div class="container">
        <div class="flex items-center relative">
            <div class="text-center flex-grow mx-auto max-w-[800px] ml-[50%]">
                <h1>REKAP PEMBAYARAN SPP</h1>
                <h1>SANTRI MADRASATUL QUR'AN AL-AMIN</h1>
            </div>
        </div>

        <h1 class="mt-4">Kelas: {{ $kelas->nama }}</h1>
        <table class="table-auto w-full mt-4 border-collapse border border-gray-800">
            <thead>
                <tr>
                    <th class="border border-gray-800 p-2">No</th>
                    <th class="border border-gray-800 p-2">Nama</th>
                    @foreach ($bulan as $key => $bln)
                        <th class="border border-gray-800 p-2">{{ $bln }}</th>
                    @endforeach
                </tr>
            </thead>
            @php
                $total_per_bulan = array_fill(1, 12, 0); // Inisialisasi array total per bulan
            @endphp
            <tbody>
                @foreach ($santris as $item)
                    <tr>
                        <td class="border border-gray-800 p-2">{{ $loop->iteration }}.</td>
                        <td class="border border-gray-800 p-2">{{ $item->nama_lengkap }}</td>
                        @foreach ($bulan as $key => $bln)
                            @php
                                $spp = $item
                                    ->spp()
                                    ->whereMonth('tanggal', $key)
                                    ->whereYear('created_at', date('Y'))
                                    ->get();
                                $status = '';
                                $tanggal = '';
                                $nominal = 0;

                                if ($spp->count() > 0) {
                                    foreach ($spp as $data) {
                                        if ($data->status == 'Sudah Lunas') {
                                            $status = 'Lunas';
                                            $tanggal = date('d-m', strtotime($data->tanggal));
                                            $nominal = $data->nominal;
                                        }
                                        // Menambahkan ke total per bulan
                                        $total_per_bulan[$key] += $nominal;
                                    }
                                }
                            @endphp
                            <td class="border border-gray-800 p-2 text-center">
                                <span>
                                    {{ $tanggal }}
                                </span>
                                <hr class="border-dashed border-gray-800">
                                <span>
                                    {{ number_format($nominal, 0, ',', '.') }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-center font-bold">Jumlah</td>
                    @foreach ($bulan as $key => $bln)
                        <td class="border border-gray-800 p-2 text-center font-bold">
                            {{ number_format($total_per_bulan[$key], 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
