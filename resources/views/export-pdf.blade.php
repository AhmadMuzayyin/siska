<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - {{ $title }}</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        body { font-family: sans-serif; font-size: 11pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #666; padding: 8px; text-align: left; font-size: 10pt; }
        th { background-color: #f0f0f0; text-align: center; }
        .text-center { text-align: center; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan {{ $title }}</h2>
        <p style="margin: 4px 0 0 0; font-size: 10pt;">Tanggal Cetak: {{ date('d M Y H:i') }}</p>
    </div>

    @if ($type === 'guru')
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Email</th>
                    <th>Jenis Kelamin</th>
                    <th>Telepon</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $idx => $g)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $g->nip ?? '-' }}</td>
                        <td>{{ $g->user->name ?? '-' }}</td>
                        <td>{{ $g->user->email ?? '-' }}</td>
                        <td>{{ ucfirst($g->jenis_kelamin?->value ?? '-') }}</td>
                        <td>{{ $g->telepon ?? '-' }}</td>
                        <td>{{ ucfirst($g->status?->value ?? 'aktif') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($type === 'santri')
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>No. Induk</th>
                    <th>Nama Lengkap</th>
                    <th>Lembaga</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $idx => $s)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $s->noinduk }}</td>
                        <td>{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->lembaga?->nama ?? '-' }}</td>
                        <td>{{ $s->kelas?->nama ?? '-' }}</td>
                        <td>{{ ucfirst($s->jenis_kelamin?->value ?? '-') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $s->status?->value ?? 'aktif')) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($type === 'nilai')
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Nilai</th>
                    <th>Predikat</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $idx => $n)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $n->santri?->nama_lengkap ?? '-' }}</td>
                        <td>{{ $n->santri?->kelas?->nama ?? '-' }}</td>
                        <td>{{ $n->mapel?->nama ?? '-' }}</td>
                        <td class="text-center"><strong>{{ $n->nilai }}</strong></td>
                        <td class="text-center">{{ $n->predikat?->value ?? '-' }}</td>
                        <td>{{ $n->semester?->tahunAkademik?->nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
