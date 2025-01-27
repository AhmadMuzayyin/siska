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

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        @page {
            size: legal portrait;
            /* F4 in portrait orientation */
            margin: 1cm;
        }

        body {
            width: 100%;
            height: 100%;
        }

        @media print {
            body {
                width: 215.9mm;
                /* F4 width in portrait */
                height: 330.2mm;
                /* F4 height in portrait */
            }
        }

        .note {
            font-family: Calibri;
            font-size: 15px;
            font-style: normal;
            font-variant: normal;
            font-weight: 100;
            line-height: 16.5px;
        }
    </style>
    <title>Print Laporan Hasil Belajar</title>
</head>


<body class="p-2">
    <div class="flex items-center relative">
        <img src="{{ asset($logo) }}" alt="Logo Sekolah" class="h-[100px] w-auto absolute left-[60px]">
        <div class="text-center flex-grow mx-auto max-w-[800px] ml-[20%]">
            <h5 class="m-0 font-bold text-center text-[1.1rem]">YAYASAN PENDIDIKAN & SOSIAL
            </h5>
            <h6 class="m-0 font-bold text-center text-base">MADRASATUL QUR'AN AL-AMIN</h6>
            <p class="m-0 text-center text-[0.8rem]">
                NSM : {{ $nsm ?? '' }}
            </p>
            <p class="m-0 text-center text-[0.8rem]">
                {{ $alamat ?? '' }}
                <br>
                Telp. {{ $telepon ?? '' }} {{ $email ?? '' }} {{ env('APP_URL') }}
            </p>
        </div>
    </div>
    <hr class="my-2 border-[2px] border-black">
    <div class="flex justify-center">
        <h1 class="text-center text-lg font-bold">
            LAPORAN HASIL BELAJAR
        </h1>
    </div>
    <div class="grid grid-cols-[auto,1fr,auto,auto] gap-4 w-full mt-4">
        <div>
            <p class="m-0">NAMA SANTRI</p>
            <p class="m-0">NOMOR INDUK</p>
            <p class="m-0">ALAMAT</p>
        </div>
        <div>
            <p class="m-0 font-bold text-sm whitespace-nowrap">
                : {{ strtoupper($nilai[0]->santri->nama_lengkap ?? '') }}
            </p>
            <p class="m-0">: {{ $nilai[0]->santri->noinduk ?? '' }}</p>
            <p class="m-0">: {{ Str::words($nilai[0]->santri->alamat ?? '', 2, '') }}</p>
        </div>
        <div class="pl-8">
            <p class="m-0">KELAS</p>
            <p class="m-0">SEMESTER</p>
            <p class="m-0">TAHUN PELAJARAN</p>
        </div>
        <div class="pl-8">
            <p class="m-0">: {{ strtoupper($nilai[0]->santri->kelas->nama ?? '') }}</p>
            <p class="m-0">: {{ $nilai[0]->semester->tipe ?? '' }}</p>
            <p class="m-0">: {{ $nilai[0]->semester->tahunAkademik->nama ?? '' }}</p>
        </div>
    </div>

    <table
        class="w-full border-collapse border border-gray-300 text-md p-2 [&_th]:border [&_th]:border-gray-300 [&_td]:border [&_td]:border-gray-300 [&_th]:border-t-[3px] [&_th]:border-t-black mt-4">
        <thead>
            <tr>
                <th class="" rowspan="2">NO</th>
                <th class="" rowspan="2">MATA PELAJARAN</th>
                <th class="" rowspan="2">KITAB</th>
                <th class="" rowspan="2">KKM</th>
                <th class="" colspan="3">NILAI</th>
                <th class="" rowspan="2">PREDIKAT</th>
                <th class="" rowspan="2">KET.</th>
            </tr>
            <tr>
                <td class="font-bold text-center">ANGKA</td>
                <td colspan="2" class="font-bold text-center">HURUF</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($nilai as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->mapel->nama ?? '' }}</td>
                    <td class="text-right note">{{ $item->mapel->kitab ?? '' }}</td>
                    <td class="text-center">{{ $item->mapel->kkm ?? '' }}</td>
                    <td class="text-center font-bold">{{ $item->nilai ?? '' }}</td>
                    <td colspan="2" class="text-center">{{ $item->nilai_huruf ?? '' }}</td>
                    <td class="text-center font-bold">{{ $item->predikat ?? '' }}</td>
                    <td class="text-center">{{ $item->keterangan ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-center font-bold">JUMLAH</td>
                <td class="text-center font-bold">{{ $nilai->sum('nilai') ?? '' }}</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center font-bold">RATA-RATA</td>
                <td class="text-center font-bold">
                    {{ $nilai->count() > 0 ? number_format($nilai->sum('nilai') / $nilai->count(), 2) : '' }}
                </td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-center font-bold">PERINGKAT</td>
                <td class="text-center font-bold"></td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
    <div class="flex gap-2">
        {{-- table nilai akhlak --}}
        <table
            class="w-1/2 border-collapse border border-gray-300 text-sm [&_th]:border [&_th]:border-gray-300 [&_td]:border [&_td]:border-gray-300 [&_th]:border-t-[3px] [&_th]:border-t-black mt-2">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KEPRIBADIAN</th>
                    <th>NILAI</th>
                    <th>PREDIKAT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">Kelakuan</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">Kerajinan</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="text-center">Kerapihan</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td class="text-center"></td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                </tr>
            </tbody>
        </table>
        {{-- table absensi --}}
        <table
            class="w-1/2 border-collapse border border-gray-300 text-sm [&_th]:border [&_th]:border-gray-300 [&_td]:border [&_td]:border-gray-300 [&_th]:border-t-[3px] [&_th]:border-t-black mt-4">
            <thead>
                <tr>
                    <th>NO</th>
                    <th colspan="2">KETIDAKHADIRAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-left pl-2">IZIN</td>
                    @php
                        $izin = $absensi->where('status', 'Izin')->count();
                    @endphp
                    <td class="text-center">{{ $izin == 0 ? '' : $izin }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-left pl-2">SAKIT</td>
                    @php
                        $sakit = $absensi->where('status', 'Sakit')->count();
                    @endphp
                    <td class="text-center">{{ $sakit == 0 ? '' : $sakit }}</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="text-left pl-2">ALPA</td>
                    @php
                        $alpa = $absensi->where('status', 'Alpa')->count();
                    @endphp
                    <td class="text-center">{{ $alpa == 0 ? '' : $alpa }}</td>
                </tr>
                <tr>
                    <td class="text-center font-bold" colspan="2">JUMLAH</td>
                    <td class="text-center" colspan="2">
                        @php
                            $total = $izin + $sakit + $alpa;
                        @endphp
                        {{ $total == 0 ? '' : $total }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <table
        class="w-full border-collapse border border-gray-300 text-md [&_th]:border [&_th]:border-gray-300 [&_td]:border [&_td]:border-gray-300 [&_th]:border-t-[3px] [&_th]:border-t-black mt-4">
        <thead>
            <tr>
                <th>CATATAN / SARAN WALI KELAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">
                    <span class="note text-md font-bold mt-2">
                        اَلّٰلهُمَّ امْلَاءْ قُلُوْبَ تَلَامِيْذِنَا وَتَلَامِيْذَاتِنَا نُوْرًا وَحِكْمَةً وَاَهْلِهِمْ
                        لِقَبُوْلِ نِعْمَةٍ وَاَصْلِحْهُمْ وَاَصْلِحْ بِهِمُ الْأُمَّةُ
                    </span>
                    <span class="block text-md note mt-2">
                        Artinya : "Ya Allah, penuhilah hati santri-santri kami dengan cahaya dan
                        hikmah, dan jadikan
                        mereka
                        hamba-hamba-Mu yang pantas menerima nikmat, dan perbaikilah diri mereka dan perbaiki pula umat
                        ini
                        melalui mereka."
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="w-full mt-8 text-md border-collapse">
        <tr>
            <!-- Kolom Orang Tua / Wali -->
            <td class="w-1/3 text-center align-top">
                <div class="mt-9">Orang Tua / Wali</div>
                <div class="mt-24 font-bold underline">
                    {{ strtoupper($nilai[0]->santri->nama_ayah ?? '') }}
                </div>
            </td>

            <!-- Kolom Wali Kelas -->
            <td class="w-1/3 text-center align-top">
                <div class="mt-9">Wali Kelas</div>
                <div class="mt-24 font-bold underline">
                    {{ strtoupper($nilai[0]->santri->kelas->guru->user->name ?? '') }}
                </div>
                <div class="mt-2">NIP. -</div>
            </td>

            <!-- Kolom Kepala Madrasah -->
            <td class="w-1/3 text-center align-top">
                <div>Banyuwangi, {{ date('d F Y') }}</div>
                <div class="mt-2">Kepala Madrasah</div>
                <div class="mt-24 font-bold underline">
                    {{ strtoupper(\App\Models\User::where('role', 'kepala')->first()->name ?? '') }}
                </div>
                <div class="mt-2">NIP.</div>
            </td>
        </tr>
    </table>

    {{-- <script>
        window.print();
        setTimeout(() => {
            window.close();
        }, 1000);
    </script> --}}
</body>

</html>
