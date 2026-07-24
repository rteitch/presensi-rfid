<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Kelas {{ $class->nama_kelas }} - {{ $bulan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 4px; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; font-size: 10px; text-transform: uppercase; text-align: center; font-weight: bold; }
        td.num { text-align: center; font-weight: bold; }
        tr.alert td { background-color: #fff3cd; }
        .badge-terlambat { color: #b45309; font-weight: bold; }
        .badge-alpha { color: #dc2626; font-weight: bold; }
        .badge-hadir { color: #16a34a; font-weight: bold; }
        tfoot td { font-weight: bold; background: #f9f9f9; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP PRESENSI KELAS {{ $class->nama_kelas }}</h2>
        <p>Wali Kelas: <strong>{{ $class->waliKelas->name ?? 'Belum Ditentukan' }}</strong> &bull; Tahun Ajaran: {{ $class->academicYear->nama ?? '-' }}</p>
        <p>Periode Bulan: <strong>{{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</strong> &bull; Dicetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th style="text-align:left">Nama Siswa</th>
                <th style="text-align:left">NIS</th>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>Total Kehadiran</th>
                <th style="text-align:left">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($students as $s)
                @php
                    $totalAtt = $s->stat_hadir + $s->stat_terlambat;
                    $isAlert = $s->stat_terlambat >= 3 || $s->stat_alpha >= 2;
                    $catatan = [];
                    if ($s->stat_terlambat >= 3) $catatan[] = 'Terlambat >=3x';
                    if ($s->stat_alpha >= 2) $catatan[] = 'Alpha >=2x';
                @endphp
                <tr class="{{ $isAlert ? 'alert' : '' }}">
                    <td class="num">{{ $no++ }}</td>
                    <td><strong>{{ $s->nama }}</strong></td>
                    <td>{{ $s->nis }}</td>
                    <td class="num badge-hadir">{{ $s->stat_hadir }}</td>
                    <td class="num badge-terlambat">{{ $s->stat_terlambat }}</td>
                    <td class="num">{{ $s->stat_izin }}</td>
                    <td class="num">{{ $s->stat_sakit }}</td>
                    <td class="num badge-alpha">{{ $s->stat_alpha }}</td>
                    <td class="num">{{ $totalAtt }}</td>
                    <td>{{ implode(', ', $catatan) ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="10" style="text-align:center;color:#999">Belum ada siswa di kelas ini.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total Kelas</td>
                <td>{{ $students->sum('stat_hadir') }}</td>
                <td>{{ $students->sum('stat_terlambat') }}</td>
                <td>{{ $students->sum('stat_izin') }}</td>
                <td>{{ $students->sum('stat_sakit') }}</td>
                <td>{{ $students->sum('stat_alpha') }}</td>
                <td>{{ $students->sum(fn($s) => $s->stat_hadir + $s->stat_terlambat) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top:12px;font-size:9px;color:#888">
        * Baris berwarna kuning = siswa memerlukan perhatian khusus (Terlambat ≥ 3x atau Alpha ≥ 2x dalam sebulan).
    </p>
</body>
</html>
