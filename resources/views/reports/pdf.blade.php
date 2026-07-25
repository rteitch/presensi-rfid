<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi RFID {{ $bulan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 4px 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 11px; text-transform: uppercase; }
        .status-hadir { color: #16a34a; font-weight: bold; }
        .status-terlambat { color: #d97706; font-weight: bold; }
        .status-izin { color: #2563eb; font-weight: bold; }
        .status-pulang_cepat { color: #0891b2; font-weight: bold; }
        .status-dispensasi { color: #0d9488; font-weight: bold; }
        .status-sakit { color: #4f46e5; font-weight: bold; }
        .status-alpha { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PRESENSI SISWA</h2>
        <p>Periode Bulan: {{ $bulan }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $att)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $att->tanggal }}</td>
                    <td>{{ $att->student->nis ?? '-' }}</td>
                    <td>{{ $att->student->nama ?? '-' }}</td>
                    <td>{{ $att->student->schoolClass->nama_kelas ?? '-' }}</td>
                    <td>{{ $att->jam_masuk ?? '-' }}</td>
                    <td>{{ $att->jam_pulang ?? '-' }}</td>
                    @php
                        $pdfLabel = match($att->status) {
                            'pulang_cepat' => 'Pulang Cepat',
                            'dispensasi'   => 'Dispensasi',
                            default        => ucfirst($att->status),
                        };
                    @endphp
                    <td class="status-{{ $att->status }}">{{ $pdfLabel }}</td>
                    <td>{{ $att->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #999;">Tidak ada data presensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
