<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Siswa {{ $bulan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 4px; font-size: 16px; }
        .header p { margin: 0; color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; font-size: 10px; text-transform: uppercase; text-align: center; }
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
        <h2>REKAP PRESENSI SISWA</h2>
        <p>Periode Bulan: <strong>{{ $bulan }}</strong> &bull; Dicetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="text-align:left">Nama Siswa</th>
                <th style="text-align:left">NIS</th>
                <th style="text-align:left">Kelas</th>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>Total</th>
                <th style="text-align:left">No HP Ortu</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($students as $s)
                @php
                    $total = $s->total_hadir + $s->total_terlambat + $s->total_izin + $s->total_sakit + $s->total_alpha;
                    $isAlert = $s->total_terlambat >= 3 || $s->total_alpha >= 2;
                @endphp
                <tr class="{{ $isAlert ? 'alert' : '' }}">
                    <td class="num">{{ $no++ }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->schoolClass->nama_kelas ?? '-' }}</td>
                    <td class="num badge-hadir">{{ $s->total_hadir }}</td>
                    <td class="num badge-terlambat">{{ $s->total_terlambat }}</td>
                    <td class="num">{{ $s->total_izin }}</td>
                    <td class="num">{{ $s->total_sakit }}</td>
                    <td class="num badge-alpha">{{ $s->total_alpha }}</td>
                    <td class="num">{{ $total }}</td>
                    <td>{{ $s->no_hp_ortu ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="11" style="text-align:center;color:#999">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td>{{ $students->sum('total_hadir') }}</td>
                <td>{{ $students->sum('total_terlambat') }}</td>
                <td>{{ $students->sum('total_izin') }}</td>
                <td>{{ $students->sum('total_sakit') }}</td>
                <td>{{ $students->sum('total_alpha') }}</td>
                <td>{{ $students->sum(fn($s)=>$s->total_hadir+$s->total_terlambat+$s->total_izin+$s->total_sakit+$s->total_alpha) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top:12px;font-size:9px;color:#999">
        * Baris berwarna kuning = siswa perlu perhatian (Terlambat ≥ 3x atau Alpha ≥ 2x)
    </p>
</body>
</html>
