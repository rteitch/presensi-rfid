<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('students.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detail Profil & Rekap Presensi</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi siswa, kontak orang tua, dan riwayat presensi harian.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.edit', $student) }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Profil</span>
                </a>
                <a href="{{ route('students.index') }}" class="btn-secondary">
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Card Profile Header Siswa -->
        <div class="page-card p-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    <img src="{{ $student->foto_url }}" alt="{{ $student->nama }}" class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-200 shadow-sm shrink-0">
                    
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $student->nama }}</h2>
                            <span class="badge {{ $student->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                            <span class="flex items-center gap-1 font-mono font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200">
                                NIS: {{ $student->nis }}
                            </span>
                            <span class="flex items-center gap-1">
                                Kelas: <strong class="text-slate-800">{{ $student->schoolClass->nama_kelas ?? '-' }}</strong>
                            </span>
                            <span class="flex items-center gap-1 font-mono bg-indigo-50 text-indigo-700 px-2.5 py-0.5 rounded-md border border-indigo-100 font-bold">
                                RFID: {{ $student->rfid_uid ?: 'Belum Dimapping' }}
                            </span>
                        </div>

                        <div class="pt-1 flex items-center gap-4 text-xs text-slate-600">
                            <div>
                                Orang Tua: <strong class="text-slate-900">{{ $student->nama_ortu ?: '-' }}</strong>
                            </div>
                            <div>
                                No HP Ortu: <span class="font-mono text-slate-800 font-semibold">{{ $student->no_hp_ortu ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($student->wa_number)
                @php
                    $namaOrtu = $student->nama_ortu ?: 'Bapak/Ibu';
                    $kelas = $student->schoolClass->nama_kelas ?? '-';
                    $appName = $schoolSettings['app_name'] ?? 'Sekolah';
                    $periodeLabel = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y');
                    $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nMenginfokan catatan presensi ananda *{$student->nama}* (Kelas {$kelas}) bulan {$periodeLabel}:\n\nHadir: *{$stats['total_hadir']}x*\nTerlambat: *{$stats['total_terlambat']}x*\nIzin: *{$stats['total_izin']}x*\nSakit: *{$stats['total_sakit']}x*\nAlpha: *{$stats['total_alpha']}x*\n\nTerima kasih.\n_{$appName}_";
                    $waUrl = 'https://wa.me/' . $student->wa_number . '?text=' . rawurlencode($msg);
                @endphp
                <a href="{{ $waUrl }}" target="_blank"
                   class="btn-primary shrink-0 bg-emerald-600 hover:bg-emerald-700 text-white font-bold border-0 shadow">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>Kirim Rekap WA Ortu</span>
                </a>
                @endif

            </div>
        </div>

        <!-- Filter Bulan & Stat Cards Grid -->
        <div class="page-card p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Ringkasan Kehadiran Bulan {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Statistik kehadiran dan tingkat Kedisiplinan siswa.</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <form method="GET" action="{{ route('students.show', $student) }}" class="flex items-center gap-2">
                        <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" class="form-input text-xs py-1.5 px-3">
                    </form>
                    <a href="{{ route('students.export-attendance', $student) }}?bulan={{ $bulan }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download Excel</span>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Hadir</span>
                    <div class="text-2xl font-black text-emerald-900 mt-1">{{ number_format($stats['total_hadir']) }}<span class="text-xs font-normal text-emerald-700"> hari</span></div>
                </div>

                <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Terlambat</span>
                    <div class="text-2xl font-black text-amber-900 mt-1">{{ number_format($stats['total_terlambat']) }}<span class="text-xs font-normal text-amber-700"> kali</span></div>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-800">Izin</span>
                    <div class="text-2xl font-black text-blue-900 mt-1">{{ number_format($stats['total_izin']) }}<span class="text-xs font-normal text-blue-700"> hari</span></div>
                </div>

                <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100 flex flex-col justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-800">Sakit</span>
                    <div class="text-2xl font-black text-indigo-900 mt-1">{{ number_format($stats['total_sakit']) }}<span class="text-xs font-normal text-indigo-700"> hari</span></div>
                </div>

                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 flex flex-col justify-between col-span-2 sm:col-span-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-800">Alpha</span>
                    <div class="text-2xl font-black text-rose-900 mt-1">{{ number_format($stats['total_alpha']) }}<span class="text-xs font-normal text-rose-700"> hari</span></div>
                </div>
            </div>

            <!-- Progress Kedisiplinan -->
            <div class="pt-2">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-1.5">
                    <span>Tingkat Kehadiran Teratat</span>
                    <span class="font-bold text-indigo-600 text-sm">{{ $pctHadir }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" style="width: {{ max($pctHadir, 3) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Log Presensi -->
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-900 text-sm">Riwayat Presensi Harian ({{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }})</h3>
                <span class="text-xs text-slate-500 font-bold bg-white px-2.5 py-1 rounded-lg border border-slate-200">Total: {{ $attendances->count() }} Data</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th class="text-center">Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($attendances as $att)
                            <tr class="table-row">
                                <td class="font-bold text-slate-900 text-xs font-mono whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($att->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="text-xs font-semibold text-slate-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($att->tanggal)->translatedFormat('l') }}
                                </td>
                                <td class="text-xs font-mono text-slate-700 font-semibold whitespace-nowrap">
                                    {{ $att->jam_masuk ? \Carbon\Carbon::parse($att->jam_masuk)->format('H:i:s') : '—' }}
                                </td>
                                <td class="text-xs font-mono text-slate-700 font-semibold whitespace-nowrap">
                                    {{ $att->jam_pulang ? \Carbon\Carbon::parse($att->jam_pulang)->format('H:i:s') : '—' }}
                                </td>
                                <td class="text-center whitespace-nowrap">
                                    @if($att->status === 'hadir')
                                        <span class="badge badge-green font-bold">Hadir</span>
                                    @elseif($att->status === 'terlambat')
                                        <span class="badge badge-amber font-bold">Terlambat</span>
                                    @elseif($att->status === 'izin')
                                        <span class="badge badge-blue font-bold">Izin</span>
                                    @elseif($att->status === 'sakit')
                                        <span class="badge badge-indigo font-bold">Sakit</span>
                                    @else
                                        <span class="badge badge-red font-bold">Alpha</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $att->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                    Belum ada data presensi siswa pada bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
