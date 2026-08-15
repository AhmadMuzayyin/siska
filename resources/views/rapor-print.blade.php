<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri - {{ $nama }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 13pt;
            font-weight: normal;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .grade-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .footer td {
            text-align: center;
            vertical-align: top;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $lembaga }}</h2>
        <h3>RAPOR CAPAIAN HASIL BELAJAR SANTRI</h3>
        <p style="margin: 3px 0 0 0; font-size: 10pt;">Tahun Akademik {{ $tahun_akademik }} - Semester {{ $semester }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Nama Santri</strong></td>
            <width="2%">:</td>
            <td width="33%">{{ $nama }}</td>
            <td width="15%"><strong>Kelas</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ $kelas }}</td>
        </tr>
        <tr>
            <td><strong>No. Induk</strong></td>
            <td>:</td>
            <td>{{ $noinduk }}</td>
            <td><strong>RFID/NISN</strong></td>
            <td>:</td>
            <td>{{ $nisn }}</td>
        </tr>
    </table>

    <table class="grade-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Mata Pelajaran</th>
                <th width="10%">Nilai</th>
                <th width="10%">Predikat</th>
                <th width="45%">Deskripsi Capaian</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mapel_rows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }} <br><small style="color: #555;"><i>{{ $row['kitab'] }}</i></small></td>
                    <td class="text-center"><strong>{{ $row['nilai'] }}</strong></td>
                    <td class="text-center">{{ $row['predikat'] }}</td>
                    <td>{{ $row['deskripsi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Belum ada data nilai mata pelajaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td width="33%">
                <p>Wali Santri,</p>
                <br><br><br>
                <p>____________________</p>
            </td>
            <td width="33%">
                <p>Wali Kelas,</p>
                <br><br><br>
                <p>____________________</p>
            </td>
            <td width="33%">
                <p>Kepala Madrasah,</p>
                <br><br><br>
                <p><strong>____________________</strong></p>
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
