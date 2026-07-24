<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Leaderboard Siswa Terlambat</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Daftar peringkat kedisiplinan dan tingkat keterlambatan siswa.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.rekap') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Rekap Per Siswa</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Bar -->
        <div class="page-card p-5 bg-slate-50/50">
            <form method="GET" action="{{ route('reports.leaderboard') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="form-label">Filter Bulan (Kosongkan = All-time)</label>
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
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1 justify-center">Tampilkan</button>
                    @if($bulan || $classId)
                        <a href="{{ route('reports.leaderboard') }}" class="btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Banner Info -->
        <div class="rounded-2xl p-6 text-white shadow-lg flex items-center gap-5" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0 shadow-inner">
                <svg class="w-7 h-7 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-black tracking-tight">Statistik Peringkat Keterlambatan</h3>
                <p class="text-amber-100 text-xs mt-0.5 font-medium">
                    @if($bulan)
                        Periode: <strong>{{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</strong>
                    @else
                        Menampilkan seluruh riwayat presensi (All-time)
                    @endif
                    @if($classId)
                        &bull; Kelas: <strong>{{ $classes->find($classId)?->nama_kelas }}</strong>
                    @endif
                </p>
            </div>
        </div>

        <!-- Podium Top 3 (Auto-Centering Flex Layout) -->
        @if($students->isNotEmpty())
        <div class="flex flex-col md:flex-row justify-center items-end gap-6 my-4">

            {{-- Rank #2 (Silver - Left) --}}
            @if($students->count() >= 2)
            <div class="page-card p-5 text-center flex flex-col items-center border-t-4 border-t-slate-400 min-h-[280px] w-full md:w-72 justify-between order-2 md:order-1">
                <div class="w-full flex flex-col items-center">
                    <span class="px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-extrabold text-xs mb-3">🥈 Rank #2</span>
                    <img src="{{ $students[1]->foto_url }}" class="w-16 h-16 rounded-full object-cover border-2 border-slate-300 shadow-md mb-2">
                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $students[1]->nama }}</div>
                    <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $students[1]->schoolClass->nama_kelas ?? '-' }}</div>
                </div>
                <div class="my-2 text-center">
                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $students[1]->total_terlambat }}<span class="text-xs text-slate-400 font-normal">x</span></div>
                    <div class="text-[11px] text-slate-500 font-semibold uppercase">Total Terlambat</div>
                </div>
                @if($students[1]->wa_number)
                @php
                    $s = $students[1];
                    $namaOrtu = $s->nama_ortu ?: 'Bapak/Ibu';
                    $kelas = $s->schoolClass->nama_kelas ?? '-';
                    $periodeLabel = $bulan ? \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') : 'seluruh waktu';
                    $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nKami menginformasikan bahwa ananda *{$s->nama}* (Kelas {$kelas}) terdaftar dalam Top 3 Siswa Terlambat periode {$periodeLabel}.\n\nTotal keterlambatan: *{$s->total_terlambat}x*.\n\nMohon bimbingan Bapak/Ibu agar ananda lebih disiplin.\nTerima kasih.\n_{$schoolSettings['app_name']}_";
                    $waUrl = 'https://wa.me/' . $s->wa_number . '?text=' . rawurlencode($msg);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                    <span>Hubungi Ortu</span>
                </a>
                @endif
            </div>
            @endif

            {{-- Rank #1 (Gold - Center, Highlighted) --}}
            <div class="page-card p-6 text-center flex flex-col items-center border-t-4 border-t-amber-400 bg-gradient-to-b from-amber-50 to-white shadow-xl min-h-[320px] w-full md:w-80 justify-between order-1 md:order-2">
                <div class="w-full flex flex-col items-center">
                    <span class="px-3.5 py-1.5 rounded-full bg-amber-400 text-amber-950 font-black text-xs mb-3 shadow">🥇 Rank #1 Terbanyak</span>
                    <img src="{{ $students[0]->foto_url }}" class="w-20 h-20 rounded-full object-cover border-4 border-amber-400 shadow-lg mb-2">
                    <div class="font-extrabold text-slate-900 text-base leading-tight">{{ $students[0]->nama }}</div>
                    <div class="text-xs text-slate-600 font-semibold mt-0.5">{{ $students[0]->schoolClass->nama_kelas ?? '-' }}</div>
                </div>
                <div class="my-2 text-center">
                    <div class="text-4xl font-black text-amber-600 tracking-tight">{{ $students[0]->total_terlambat }}<span class="text-sm text-amber-800 font-normal">x</span></div>
                    <div class="text-[11px] text-amber-800 font-bold uppercase tracking-wider">Paling Sering Terlambat</div>
                </div>
                @if($students[0]->wa_number)
                @php
                    $s = $students[0];
                    $namaOrtu = $s->nama_ortu ?: 'Bapak/Ibu';
                    $kelas = $s->schoolClass->nama_kelas ?? '-';
                    $periodeLabel = $bulan ? \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') : 'seluruh waktu';
                    $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nKami menginformasikan bahwa ananda *{$s->nama}* (Kelas {$kelas}) menduduki Peringkat #1 Siswa Terlambat periode {$periodeLabel}.\n\nTotal keterlambatan: *{$s->total_terlambat}x*.\n\nMohon perhatian serius Bapak/Ibu demi kedisiplinan ananda.\nTerima kasih.\n_{$schoolSettings['app_name']}_";
                    $waUrl = 'https://wa.me/' . $s->wa_number . '?text=' . rawurlencode($msg);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition shadow-lg flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                    <span>Hubungi Ortu (Rank #1)</span>
                </a>
                @endif
            </div>

            {{-- Rank #3 (Bronze - Right) --}}
            @if($students->count() >= 3)
            <div class="page-card p-5 text-center flex flex-col items-center border-t-4 border-t-amber-700 min-h-[280px] w-full md:w-72 justify-between order-3 md:order-3">
                <div class="w-full flex flex-col items-center">
                    <span class="px-3 py-1 rounded-full bg-amber-100 border border-amber-200 text-amber-900 font-extrabold text-xs mb-3">🥉 Rank #3</span>
                    <img src="{{ $students[2]->foto_url }}" class="w-16 h-16 rounded-full object-cover border-2 border-amber-300 shadow-md mb-2">
                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $students[2]->nama }}</div>
                    <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $students[2]->schoolClass->nama_kelas ?? '-' }}</div>
                </div>
                <div class="my-2 text-center">
                    <div class="text-3xl font-black text-amber-800 tracking-tight">{{ $students[2]->total_terlambat }}<span class="text-xs text-slate-400 font-normal">x</span></div>
                    <div class="text-[11px] text-slate-500 font-semibold uppercase">Total Terlambat</div>
                </div>
                @if($students[2]->wa_number)
                @php
                    $s = $students[2];
                    $namaOrtu = $s->nama_ortu ?: 'Bapak/Ibu';
                    $kelas = $s->schoolClass->nama_kelas ?? '-';
                    $periodeLabel = $bulan ? \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') : 'seluruh waktu';
                    $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nKami menginformasikan bahwa ananda *{$s->nama}* (Kelas {$kelas}) terdaftar dalam Top 3 Siswa Terlambat periode {$periodeLabel}.\n\nTotal keterlambatan: *{$s->total_terlambat}x*.\n\nMohon bimbingan Bapak/Ibu.\nTerima kasih.\n_{$schoolSettings['app_name']}_";
                    $waUrl = 'https://wa.me/' . $s->wa_number . '?text=' . rawurlencode($msg);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                    <span>Hubungi Ortu</span>
                </a>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Full Ranking Table -->
        @if($students->isNotEmpty())
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Peringkat Lengkap Siswa Terlambat</h3>
                <span class="text-xs text-slate-500 font-bold">Total: {{ $students->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="text-center">Rank</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center text-amber-700">Terlambat</th>
                            <th class="text-center text-emerald-700">Hadir</th>
                            <th class="text-center text-rose-700">Alpha</th>
                            <th class="text-center">Visual Progress</th>
                            <th class="text-center">Aksi WA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $s)
                            @php
                                $rankNum = $index + 1;
                                $maxTerlambat = $students->max('total_terlambat') ?: 1;
                                $pct = $maxTerlambat > 0 ? round(($s->total_terlambat / $maxTerlambat) * 100) : 0;
                            @endphp
                            <tr class="table-row">
                                <td class="text-center font-bold text-slate-700 text-xs whitespace-nowrap">
                                    @if($rankNum === 1)
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 border border-amber-200 inline-flex items-center gap-1.5 whitespace-nowrap font-black">🥇 #1</span>
                                    @elseif($rankNum === 2)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-200 text-slate-800 border border-slate-300 inline-flex items-center gap-1.5 whitespace-nowrap font-bold">🥈 #2</span>
                                    @elseif($rankNum === 3)
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-200 text-amber-950 border border-amber-300 inline-flex items-center gap-1.5 whitespace-nowrap font-bold">🥉 #3</span>
                                    @else
                                        <span class="text-slate-500 font-mono font-bold">#{{ $rankNum }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $s->foto_url }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $s->nama }}</div>
                                            <div class="text-xs text-slate-400 font-mono">{{ $s->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-gray">{{ $s->schoolClass->nama_kelas ?? '-' }}</span></td>
                                <td class="text-center"><span class="font-black text-amber-600 text-base">{{ $s->total_terlambat }}x</span></td>
                                <td class="text-center font-bold text-emerald-700">{{ $s->total_hadir }}</td>
                                <td class="text-center font-bold text-rose-700">{{ $s->total_alpha }}</td>
                                <td class="w-36">
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 rounded-full {{ $pct >= 75 ? 'bg-rose-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width:{{ max($pct, 5) }}%"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($s->wa_number && $s->total_terlambat > 0)
                                        @php
                                            $namaOrtu = $s->nama_ortu ?: 'Bapak/Ibu';
                                            $kelas = $s->schoolClass->nama_kelas ?? '-';
                                            $periodeLabel = $bulan ? \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') : 'seluruh waktu';
                                            $msg = "Assalamu'alaikum Wr. Wb.\nYth. {$namaOrtu},\n\nMenginfokan bahwa ananda *{$s->nama}* (Kelas {$kelas}) tercatat terlambat sebanyak *{$s->total_terlambat}x* pada periode {$periodeLabel}.\n\nMohon perhatian Bapak/Ibu.\nTerima kasih.\n_{$schoolSettings['app_name']}_";
                                            $waUrl = 'https://wa.me/' . $s->wa_number . '?text=' . rawurlencode($msg);
                                        @endphp
                                        <a href="{{ $waUrl }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                            <span>WA Ortu</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="page-card p-12 text-center text-slate-400">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="font-bold text-slate-700">Tidak ada data keterlambatan</p>
            <p class="text-xs mt-1">Semua siswa hadir tepat waktu pada periode ini.</p>
        </div>
        @endif
    </div>
</x-app-layout>
