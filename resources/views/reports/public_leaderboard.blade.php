<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Kedisiplinan Siswa - {{ $schoolSettings['school_name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card-gold {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(180, 83, 9, 0.05) 100%);
            border: 1px solid rgba(245, 158, 11, 0.4);
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Top Header -->
    <header class="w-full border-b border-slate-800 bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 py-4 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                @if($schoolSettings['logo_url'])
                    <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="h-10 w-auto max-w-[120px] object-contain shrink-0 drop-shadow">
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[11px] font-extrabold tracking-wider uppercase">Papan Kedisiplinan</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[11px] font-bold">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Live Auto-Sync
                        </span>
                    </div>
                    <h1 class="text-lg font-bold text-white leading-tight mt-0.5">{{ $schoolSettings['school_name'] }}</h1>
                </div>
            </div>

            <!-- Header Action & Live Clock -->
            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col text-right">
                    <div id="live-clock" class="font-mono text-base font-bold text-amber-400">00:00:00</div>
                    <div id="live-date" class="text-[11px] text-slate-400">Senin, 1 Januari 2026</div>
                </div>
                <a href="{{ route('kiosk.scan') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m-8-8h16"/></svg>
                    <span>Layar Scan Kiosk</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl w-full mx-auto px-6 py-8 flex-1 space-y-8">
        
        <!-- Filter & Header Banner -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-900/60 p-6 rounded-3xl border border-slate-800">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 uppercase tracking-widest mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Leaderboard Publik
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Peringkat Keterlambatan Siswa</h2>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Daftar peringkat kedisiplinan berdasarkan statistik waktu kehadiran siswa.</p>
            </div>

            <!-- Filter Controls -->
            <form method="GET" action="{{ route('public.leaderboard') }}" class="flex flex-wrap items-center gap-3">
                <input type="month" name="bulan" value="{{ $bulan }}" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                
                <select name="class_id" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition shadow">
                    Filter
                </button>
                @if($bulan || $classId)
                    <a href="{{ route('public.leaderboard') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if($students->isNotEmpty())

        <!-- Top 3 Podium (Visual WOW Effect & Auto-Centering) -->
        <div class="flex flex-col md:flex-row justify-center items-end gap-6 my-4">

            {{-- Rank #2 (Silver - Left) --}}
            @if($students->count() >= 2)
            <div class="glass-card p-6 rounded-3xl text-center flex flex-col items-center border-t-4 border-t-slate-400 min-h-[300px] w-full md:w-72 justify-between transition hover:scale-102 order-2 md:order-1">
                <div class="w-full flex flex-col items-center">
                    <span class="px-3.5 py-1 rounded-full bg-slate-800 border border-slate-700 text-slate-300 font-extrabold text-xs mb-4 shadow">🥈 Rank #2</span>
                    <img src="{{ $students[1]->foto_url }}" class="w-20 h-20 rounded-full object-cover border-2 border-slate-400 shadow-xl mb-3">
                    <h3 class="font-bold text-white text-base leading-snug">{{ $students[1]->nama }}</h3>
                    <span class="text-xs text-amber-400/90 font-medium mt-1">{{ $students[1]->schoolClass->nama_kelas ?? '-' }}</span>
                </div>
                <div class="my-4 text-center">
                    <div class="text-4xl font-black text-slate-200 tracking-tight">{{ $students[1]->total_terlambat }}<span class="text-xs text-slate-400 font-normal">x</span></div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Total Terlambat</div>
                </div>
            </div>
            @endif

            {{-- Rank #1 (Gold - Highlighted Center) --}}
            <div class="glass-card-gold p-8 rounded-3xl text-center flex flex-col items-center shadow-2xl min-h-[350px] w-full md:w-80 justify-between relative overflow-hidden transform md:-translate-y-2 order-1 md:order-2">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="w-full flex flex-col items-center relative z-10">
                    <span class="px-4 py-1.5 rounded-full bg-amber-500 text-slate-950 font-black text-xs mb-4 shadow-lg tracking-wide uppercase">🥇 Rank #1 Terbanyak</span>
                    <img src="{{ $students[0]->foto_url }}" class="w-24 h-24 rounded-full object-cover border-4 border-amber-400 shadow-2xl mb-3 ring-4 ring-amber-500/20">
                    <h3 class="font-extrabold text-white text-lg leading-snug">{{ $students[0]->nama }}</h3>
                    <span class="text-xs text-amber-300 font-semibold mt-1">{{ $students[0]->schoolClass->nama_kelas ?? '-' }}</span>
                </div>
                <div class="my-4 text-center relative z-10">
                    <div class="text-5xl font-black text-amber-400 tracking-tight drop-shadow">{{ $students[0]->total_terlambat }}<span class="text-sm text-amber-200 font-normal">x</span></div>
                    <div class="text-[11px] text-amber-300/80 font-extrabold uppercase tracking-widest mt-1">Paling Sering Terlambat</div>
                </div>
            </div>

            {{-- Rank #3 (Bronze - Right) --}}
            @if($students->count() >= 3)
            <div class="glass-card p-6 rounded-3xl text-center flex flex-col items-center border-t-4 border-t-amber-700 min-h-[300px] w-full md:w-72 justify-between transition hover:scale-102 order-3 md:order-3">
                <div class="w-full flex flex-col items-center">
                    <span class="px-3.5 py-1 rounded-full bg-slate-800 border border-slate-700 text-amber-500 font-extrabold text-xs mb-4 shadow">🥉 Rank #3</span>
                    <img src="{{ $students[2]->foto_url }}" class="w-20 h-20 rounded-full object-cover border-2 border-amber-700 shadow-xl mb-3">
                    <h3 class="font-bold text-white text-base leading-snug">{{ $students[2]->nama }}</h3>
                    <span class="text-xs text-amber-400/90 font-medium mt-1">{{ $students[2]->schoolClass->nama_kelas ?? '-' }}</span>
                </div>
                <div class="my-4 text-center">
                    <div class="text-4xl font-black text-amber-600 tracking-tight">{{ $students[2]->total_terlambat }}<span class="text-xs text-slate-400 font-normal">x</span></div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Total Terlambat</div>
                </div>
            </div>
            @endif

        </div>

        <!-- Full Rankings Table (#4 to #20) -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-xl">
            <div class="px-6 py-4 border-b border-slate-800 bg-slate-900/40 flex items-center justify-between">
                <h3 class="font-extrabold text-white text-sm uppercase tracking-wider">Daftar Lengkap Peringkat Kedisiplinan</h3>
                <span class="text-xs text-slate-400 font-medium">Top {{ $students->count() }} Siswa</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-900/60">
                            <th class="py-3.5 px-6 text-center w-16">Rank</th>
                            <th class="py-3.5 px-6">Siswa</th>
                            <th class="py-3.5 px-6">Kelas</th>
                            <th class="py-3.5 px-6 text-center text-amber-400">Total Terlambat</th>
                            <th class="py-3.5 px-6 text-center text-emerald-400">Total Hadir</th>
                            <th class="py-3.5 px-6 text-center text-rose-400">Tingkat Keterlambatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-sm">
                        @foreach($students as $index => $s)
                            @php
                                $rankNum = $index + 1;
                                $maxTerlambat = $students->max('total_terlambat') ?: 1;
                                $pct = $maxTerlambat > 0 ? round(($s->total_terlambat / $maxTerlambat) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-4 px-6 text-center font-extrabold text-xs whitespace-nowrap">
                                    @if($rankNum === 1)
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30 inline-flex items-center gap-1.5 whitespace-nowrap font-black">🥇 #1</span>
                                    @elseif($rankNum === 2)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-700 text-slate-300 border border-slate-600 inline-flex items-center gap-1.5 whitespace-nowrap font-black">🥈 #2</span>
                                    @elseif($rankNum === 3)
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-900/40 text-amber-400 border border-amber-800/50 inline-flex items-center gap-1.5 whitespace-nowrap font-black">🥉 #3</span>
                                    @else
                                        <span class="text-slate-400 font-mono font-bold">#{{ $rankNum }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $s->foto_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-700 shrink-0">
                                        <div>
                                            <div class="font-bold text-white text-sm">{{ $s->nama }}</div>
                                            <div class="text-xs text-slate-400 font-medium">NIS: {{ substr($s->nis, 0, 3) }}***</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 text-xs font-semibold">
                                        {{ $s->schoolClass->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-black text-amber-400 text-base">{{ $s->total_terlambat }}x</span>
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-emerald-400">
                                    {{ $s->total_hadir }}x
                                </td>
                                <td class="py-4 px-6 w-48">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-700/50">
                                            <div class="h-2 rounded-full {{ $pct >= 75 ? 'bg-rose-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width:{{ max($pct, 5) }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-mono text-slate-400 shrink-0">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @else
        <!-- Empty State -->
        <div class="glass-card p-12 rounded-3xl text-center">
            <svg class="w-16 h-16 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="font-bold text-white text-lg">Tidak Ada Data Keterlambatan</h3>
            <p class="text-xs text-slate-400 mt-1">Luar biasa! Semua siswa hadir tepat waktu pada periode ini.</p>
        </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-800/80 py-4 px-6 text-center text-xs text-slate-500">
        <div>&copy; {{ date('Y') }} {{ $schoolSettings['school_name'] }} &bull; Powered by {{ $schoolSettings['app_name'] }}</div>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('live-clock');
            const date = document.getElementById('live-date');
            if (clock) clock.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
            if (date) date.innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Auto-refresh leaderboard every 15 seconds for live hall monitor display
        setTimeout(function() {
            window.location.reload();
        }, 15000);
    </script>
</body>
</html>
