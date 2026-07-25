<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Rekap Presensi Per Siswa</h1>
                <p class="text-sm text-slate-500 mt-0.5">Ringkasan total kehadiran per siswa bulan ini & kontak orang tua via WhatsApp.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.leaderboard') }}" class="btn-secondary" style="background:#fef3c7;color:#b45309;border-color:#fde68a">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Leaderboard Terlambat
                </a>
                <a href="{{ route('reports.rekap-pdf', ['bulan' => $bulan, 'class_id' => $classId]) }}" target="_blank" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </a>
                <a href="{{ route('reports.rekap-excel', ['bulan' => $bulan, 'class_id' => $classId]) }}" class="btn-secondary text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="page-card">
            <!-- Filter -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <form method="GET" action="{{ route('reports.rekap') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" name="bulan" value="{{ $bulan }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Filter Kelas</label>
                        <select name="class_id" class="form-input">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full justify-center">Tampilkan Rekap</button>
                    </div>
                </form>
            </div>

            <div class="px-6 py-3 bg-slate-50 border-b border-slate-100 text-xs text-slate-500">
                Periode Rekap: <strong class="text-slate-800">{{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</strong>. Siswa dengan tingkat keterlambatan/alpha tinggi ditandai dengan badge khusus.
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center text-emerald-700">Hadir</th>
                            <th class="text-center text-amber-700">Terlambat</th>
                            <th class="text-center text-blue-700">Izin</th>
                            <th class="text-center text-cyan-700">Plg. Cepat</th>
                            <th class="text-center text-teal-700">Dispensasi</th>
                            <th class="text-center text-indigo-700">Sakit</th>
                            <th class="text-center text-rose-700">Alpha</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Hubungi Ortu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $s)
                            @php
                                $total = $s->total_hadir + $s->total_terlambat + $s->total_izin + ($s->total_pulang_cepat ?? 0) + ($s->total_dispensasi ?? 0) + $s->total_sakit + $s->total_alpha;
                                $isAlert = ($s->total_terlambat >= 3) || ($s->total_alpha >= 2);
                            @endphp
                            <tr class="table-row {{ $isAlert ? 'bg-rose-50/30' : '' }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div>
                                            <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                                {{ $s->nama }}
                                                @if($isAlert)
                                                    <span class="badge badge-red text-[10px] py-0 px-1.5">Perlu Perhatian</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-400 font-mono">{{ $s->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-gray">{{ $s->schoolClass->nama_kelas ?? '-' }}</span></td>
                                <td class="text-center"><span class="badge badge-green font-bold">{{ $s->total_hadir }}</span></td>
                                <td class="text-center"><span class="badge {{ $s->total_terlambat >= 3 ? 'badge-amber font-extrabold' : 'badge-gray' }}">{{ $s->total_terlambat }}</span></td>
                                <td class="text-center"><span class="badge badge-blue font-bold">{{ $s->total_izin }}</span></td>
                                <td class="text-center"><span class="badge badge-cyan font-bold">{{ $s->total_pulang_cepat ?? 0 }}</span></td>
                                <td class="text-center"><span class="badge badge-teal font-bold">{{ $s->total_dispensasi ?? 0 }}</span></td>
                                <td class="text-center"><span class="badge badge-indigo font-bold">{{ $s->total_sakit }}</span></td>
                                <td class="text-center"><span class="badge {{ $s->total_alpha >= 2 ? 'badge-red font-extrabold' : 'badge-gray' }}">{{ $s->total_alpha }}</span></td>
                                <td class="text-center font-extrabold text-slate-800">{{ $total }}</td>
                                <td class="text-center">
                                    @if($s->wa_number && ($s->total_terlambat > 0 || $s->total_alpha > 0))
                                        @php
                                            $namaOrtu = $s->nama_ortu ?: 'Bapak/Ibu';
                                            $kelas = $s->schoolClass->nama_kelas ?? '-';
                                            $pc = $s->total_pulang_cepat ?? 0;
                                            $disp = $s->total_dispensasi ?? 0;
                                            $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nMenginfokan catatan presensi ananda *{$s->nama}* (Kelas {$kelas}) bulan {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}:\n\nHadir: *{$s->total_hadir}x*\nTerlambat: *{$s->total_terlambat}x*\nIzin: *{$s->total_izin}x*\nPulang Cepat: *{$pc}x*\nDispensasi: *{$disp}x*\nSakit: *{$s->total_sakit}x*\nAlpha: *{$s->total_alpha}x*\n\nMohon perhatian dan bimbingan Bapak/Ibu demi kedisiplinan ananda.\n\nTerima kasih.\n_{$schoolSettings['app_name']}_";
                                            $waUrl = 'https://wa.me/' . $s->wa_number . '?text=' . rawurlencode($msg);
                                        @endphp
                                        <a href="{{ $waUrl }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                            WA Ortu
                                        </a>
                                    @elseif(!$s->wa_number)
                                        <span class="text-xs text-slate-400 italic">No HP kosong</span>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400 text-sm">Belum ada data siswa aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5"><span class="badge badge-red text-[10px]">Perlu Perhatian</span> Terlambat ≥ 3x atau Alpha ≥ 2x</span>
                <span>Format otomatis menggunakan WhatsApp Web / App</span>
            </div>
        </div>
    </div>
</x-app-layout>
