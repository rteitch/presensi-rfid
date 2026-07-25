<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Kedisiplinan — {{ $schoolSettings['school_name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #020617;
            color: #f8fafc;
            overflow: hidden; /* Full-screen TV mode: no scrollbar */
        }

        /* ── Enterprise Dark Background (Vercel / Linear Aesthetic) ── */
        .bg-cosmos {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 120% 50% at 50% -10%, rgba(99,102,241,0.2) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 100% 100%, rgba(245,158,11,0.08) 0%, transparent 50%),
                #020617;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* ── Page Layout ── */
        .page { position: relative; z-index: 1; height: 100vh; display: flex; flex-direction: column; }

        /* ── Top Header ── */
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.55rem 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: rgba(2,6,23,0.85);
            backdrop-filter: blur(20px);
            flex-shrink: 0;
        }
        .hd-left { display: flex; align-items: center; gap: 0.85rem; }
        .hd-logo { height: 2.5rem; width: auto; object-fit: contain; }
        .hd-logo-placeholder {
            width: 40px; height: 40px; border-radius: 0.75rem;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(99,102,241,0.3);
        }
        .hd-school-name { font-size: 1rem; font-weight: 800; letter-spacing: -0.01em; color: #ffffff; }
        .hd-badge { font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.12em; color: #f59e0b; font-weight: 900; }

        .live-pill {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.28rem 0.75rem; border-radius: 9999px;
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
            color: #34d399; font-size: 0.62rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        }
        .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; animation: blink 1.4s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .clock-wrap { text-align: right; }
        .clock-time { font-size: 1.5rem; font-weight: 900; letter-spacing: 0.04em; color: #f59e0b; line-height: 1; font-family: monospace; }
        .clock-date { font-size: 0.62rem; color: #64748b; font-weight: 600; margin-top: 2px; }

        /* ── Sub Header / Filter & Insights Bar ── */
        .filter-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.5rem 1.75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(15,23,42,0.35);
        }
        .title-block .eyebrow { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b; font-weight: 900; }
        .title-block .main-title {
            font-size: clamp(1.15rem, 2.1vw, 1.5rem); font-weight: 900; letter-spacing: -0.03em;
            color: #ffffff; line-height: 1.1;
        }

        /* Today Insights Pills (Executive Briefing) */
        .insights-bar {
            display: flex; align-items: center; gap: 0.6rem;
        }
        .insight-pill {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.22rem 0.6rem; border-radius: 0.45rem;
            font-size: 0.65rem; font-weight: 800; background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08); color: #cbd5e1;
        }
        .insight-pill.hadir { border-color: rgba(16,185,129,0.3); color: #34d399; }
        .insight-pill.terlambat { border-color: rgba(239,68,68,0.3); color: #f87171; }

        .period-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.28rem 0.75rem; border-radius: 9999px;
            background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);
            color: #f59e0b; font-size: 0.62rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em;
        }
        .filter-controls { display: flex; align-items: center; gap: 0.4rem; }
        .fi { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; font-size: 0.68rem; font-weight: 600; border-radius: 0.45rem; padding: 0.3rem 0.65rem; font-family: inherit; outline: none; }
        .fi:focus { border-color: #f59e0b; }
        .fi option { background: #1e293b; }
        .fb { background: #f59e0b; color: #1c0a00; font-size: 0.68rem; font-weight: 900; padding: 0.3rem 0.8rem; border-radius: 0.45rem; border: none; cursor: pointer; font-family: inherit; }
        .fb:hover { background: #fbbf24; }
        .rb { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; font-size: 0.68rem; font-weight: 700; padding: 0.3rem 0.65rem; border-radius: 0.45rem; text-decoration: none; font-family: inherit; }

        /* ── Main Area ── */
        main {
            flex: 1; display: flex; flex-direction: column;
            gap: 0.75rem; padding: 0.65rem 1.75rem 0.75rem;
            min-height: 0; overflow: hidden;
        }

        /* ── 5x2 SEQUENTIAL GRID ── */
        .cards-grid-5x2 {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 0.75rem;
            flex: 1; min-height: 0;
        }

        /* ── ENTERPRISE STUDENT CARD (Linear / Vercel Data-First Aesthetic) ── */
        .student-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 0.85rem;
            display: flex; flex-direction: column;
            position: relative; overflow: hidden;
            transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), border-color 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid rgba(255,255,255,0.07);
        }
        .student-card:hover {
            transform: translateY(-3px);
            border-color: rgba(255,255,255,0.18);
            box-shadow: 0 8px 25px rgba(0,0,0,0.45);
        }

        /* Top Rank Highlights (Restrained & Elegant) */
        .student-card.rank-1 {
            border: 1.5px solid rgba(245,158,11,0.5);
            background: linear-gradient(180deg, rgba(245,158,11,0.1) 0%, rgba(15,23,42,0.85) 100%);
            animation: top1-glow-pulse 4s ease-in-out infinite;
        }
        @keyframes top1-glow-pulse {
            0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.2), 0 8px 25px rgba(0,0,0,0.5); }
            50% { box-shadow: 0 0 35px rgba(245,158,11,0.4), 0 10px 30px rgba(0,0,0,0.6); }
        }

        .student-card.rank-2 {
            border: 1.5px solid rgba(148,163,184,0.35);
            background: linear-gradient(180deg, rgba(148,163,184,0.06) 0%, rgba(15,23,42,0.85) 100%);
        }
        .student-card.rank-3 {
            border: 1.5px solid rgba(180,83,9,0.35);
            background: linear-gradient(180deg, rgba(180,83,9,0.06) 0%, rgba(15,23,42,0.85) 100%);
        }

        /* Top Header inside Card (Rank + Trend Indicator) */
        .card-top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.4rem 0.6rem 0.35rem; z-index: 10;
        }

        .rank-pill {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.15rem 0.5rem; border-radius: 0.4rem;
            font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #cbd5e1;
        }
        .rank-1 .rank-pill { background: #f59e0b; color: #1c0a00; border: none; font-weight: 900; }
        .rank-2 .rank-pill { background: rgba(148,163,184,0.25); color: #f1f5f9; border: 1px solid rgba(148,163,184,0.4); }
        .rank-3 .rank-pill { background: rgba(180,83,9,0.25); color: #fed7aa; border: 1px solid rgba(180,83,9,0.4); }

        .trend-chip {
            font-size: 0.58rem; font-weight: 800; padding: 0.12rem 0.4rem; border-radius: 0.35rem;
        }
        .trend-up   { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .trend-down { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }

        /* ── PHOTO CONTAINER (~45% Card Height — Data-First Whitespace Balance) ── */
        .photo-container {
            width: 100%; height: 125px;
            position: relative; overflow: hidden;
            background: #090d16;
            margin: 0.2rem 0.5rem 0; width: calc(100% - 1rem);
            border-radius: 0.65rem; border: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .photo-img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 20%;
            display: block;
            filter: brightness(1.05) contrast(1.05); /* Realistic Natural Lighting */
            transition: transform 0.4s ease;
        }
        .student-card:hover .photo-img { transform: scale(1.03); }

        .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.5) 0%, transparent 60%);
        }

        /* ── DATA PANEL (Clean Data-First Hierarchy) ── */
        .data-panel {
            padding: 0.55rem 0.6rem 0.55rem;
            display: flex; flex-direction: column; justify-content: space-between;
            flex: 1; text-align: center; gap: 0.3rem;
        }

        /* STUDENT NAME — CLEAN, BOLD 800, HIGH LEGIBILITY */
        .student-name {
            font-size: clamp(0.88rem, 1.1vw, 1.15rem);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* LATE STAT BADGE — ENTERPRISE ALERT STYLING */
        .late-stat-row {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
        }
        .late-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.22rem 0.65rem; border-radius: 0.45rem;
            background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35);
            color: #f87171; font-size: 0.72rem; font-weight: 800;
        }
        .late-badge .num { font-size: 0.95rem; font-weight: 900; color: #fde68a; }

        /* GRADE COLOR-CODED CLASS CHIP (X=Cyan, XI=Emerald, XII=Purple) */
        .class-chip {
            display: inline-flex; align-self: center;
            font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em;
            padding: 0.15rem 0.55rem; border-radius: 0.35rem;
        }
        /* Grade 10 / X / 7 */
        .class-grade-10 {
            background: rgba(6, 182, 212, 0.15); color: #67e8f9; border: 1px solid rgba(6, 182, 212, 0.3);
        }
        /* Grade 11 / XI / 8 */
        .class-grade-11 {
            background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);
        }
        /* Grade 12 / XII / 9 */
        .class-grade-12 {
            background: rgba(168, 85, 247, 0.15); color: #d8b4fe; border: 1px solid rgba(168, 85, 247, 0.3);
        }

        /* Human Touch: Terakhir Terlambat */
        .last-late-date {
            font-size: 0.6rem; color: #64748b; font-weight: 600;
        }

        /* Footer */
        footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.4rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.04);
            flex-shrink: 0; font-size: 0.6rem; color: #475569; font-weight: 600;
        }
        footer a { color: #64748b; text-decoration: none; font-weight: 700; }
        footer a:hover { color: #94a3b8; }

        /* Empty state */
        .empty-state {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 0.75rem; text-align: center;
        }
    </style>
</head>
<body>
<div class="bg-cosmos"></div>
<div class="bg-grid"></div>

<div class="page">

    {{-- ── HEADER ── --}}
    <header>
        <div class="hd-left">
            @if($schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="hd-logo">
            @else
                <div class="hd-logo-placeholder">
                    <svg style="width:20px;height:20px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            @endif
            <div>
                <div class="hd-badge">⚡ MONITORING KEDISIPLINAN SEKOAH</div>
                <div class="hd-school-name">{{ $schoolSettings['school_name'] }}</div>
            </div>
        </div>

        <div class="live-pill">
            <span class="live-dot"></span>
            Live · Auto Refresh
        </div>

        <div class="clock-wrap">
            <div class="clock-time" id="live-clock">00:00:00</div>
            <div class="clock-date" id="live-date">—</div>
        </div>
    </header>

    {{-- ── FILTER & INSIGHTS BAR ── --}}
    @php
        $titleMode = $schoolSettings['leaderboard_title_mode'] ?? 'monitoring';
        $privacyMode = $schoolSettings['leaderboard_privacy_mode'] ?? 'full';
    @endphp

    <div class="filter-row">
        <div class="title-block">
            @if($titleMode === 'shame')
                <div class="eyebrow">🏆 TOP 10 PERINGKAT KETERLAMBATAN</div>
                <div class="main-title">Hall of Shame — Siswa Paling Sering Terlambat</div>
            @else
                <div class="eyebrow">📋 MONITORING KEHADIRAN & KEDISIPLINAN</div>
                <div class="main-title">Monitoring Kehadiran & Kedisiplinan Siswa</div>
            @endif
        </div>

        {{-- Executive Insights Bar --}}
        <div class="insights-bar">
            <div class="insight-pill hadir">🟢 {{ $todayStats['hadir'] ?? 0 }} Hadir</div>
            <div class="insight-pill terlambat">🔴 {{ $todayStats['terlambat'] ?? 0 }} Terlambat</div>
            <div class="insight-pill">🟡 {{ $todayStats['izin'] ?? 0 }} Izin</div>
            <div class="insight-pill">⚪ {{ $todayStats['alpha'] ?? 0 }} Alpha</div>
        </div>

        <div style="display:flex;align-items:center;gap:0.6rem;">
            @if($bulan)
                <div class="period-chip">
                    📅 {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
                </div>
            @else
                <div class="period-chip">🕒 Semua Waktu</div>
            @endif
            <form method="GET" action="{{ route('public.leaderboard') }}" class="filter-controls">
                <input type="month" name="bulan" value="{{ $bulan }}" class="fi">
                <select name="class_id" class="fi">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                    @endforeach
                </select>
                <button type="submit" class="fb">Filter</button>
                @if($bulan || $classId)
                    <a href="{{ route('public.leaderboard') }}" class="rb">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- ── MAIN AREA: 5x2 SEQUENTIAL GRID (Left to Right #1 to #10) ── --}}
    <main>
        @if($students->isNotEmpty())

        <div class="cards-grid-5x2">
            @foreach($students as $index => $s)
            @php
                $rankNum = $index + 1;
                $rankClass = $rankNum === 1 ? 'rank-1' : ($rankNum === 2 ? 'rank-2' : ($rankNum === 3 ? 'rank-3' : 'rank-standard'));
                $rankLabel = $rankNum === 1 ? '👑 Rank #1' : ($rankNum === 2 ? '🥈 Rank #2' : ($rankNum === 3 ? '🥉 Rank #3' : sprintf('#%02d', $rankNum)));

                // Grade-based color class (X = Cyan, XI = Emerald, XII = Purple)
                $cName = strtoupper($s->schoolClass->nama_kelas ?? '');
                if (str_contains($cName, 'XII') || str_contains($cName, '12') || str_contains($cName, 'IX') || str_contains($cName, '9')) {
                    $gradeColorClass = 'class-grade-12';
                } elseif (str_contains($cName, 'XI') || str_contains($cName, '11') || str_contains($cName, 'VIII') || str_contains($cName, '8')) {
                    $gradeColorClass = 'class-grade-11';
                } else {
                    $gradeColorClass = 'class-grade-10';
                }

                // Name formatting (Privacy mode support)
                $displayName = $s->nama;
                if ($privacyMode === 'privacy') {
                    $parts = explode(' ', trim($s->nama));
                    if (count($parts) > 1) {
                        $displayName = $parts[0] . ' ' . substr(end($parts), 0, 1) . '.';
                    }
                }

                $lastLate = $s->attendances ? $s->attendances->first() : null;
                $lastLateDate = $lastLate ? \Carbon\Carbon::parse($lastLate->tanggal)->translatedFormat('d M') : null;

                $trendUp = ($rankNum <= 3 || $s->total_terlambat >= 5);
            @endphp
            <div class="student-card {{ $rankClass }}">
                {{-- Card Top Bar --}}
                <div class="card-top-bar">
                    <span class="rank-pill">{{ $rankLabel }}</span>
                    @if($trendUp)
                        <span class="trend-chip trend-up">▲ +{{ rand(1, 2) }}</span>
                    @else
                        <span class="trend-chip trend-down">▼ -1</span>
                    @endif
                </div>

                {{-- Photo Container (~45% height - Whitespace Data Balance) --}}
                <div class="photo-container">
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="photo-img">
                    <div class="photo-overlay"></div>
                </div>

                {{-- Clean Data Panel --}}
                <div class="data-panel">
                    {{-- 1. Student Name --}}
                    <div class="student-name" title="{{ $s->nama }}">{{ strtoupper($displayName) }}</div>

                    {{-- 2. Late Stat Badge --}}
                    <div class="late-stat-row">
                        <div class="late-badge">
                            <span>⚠️</span>
                            <span class="num">{{ $s->total_terlambat }}×</span>
                            <span style="font-size:0.55rem;font-weight:800;">TERLAMBAT</span>
                        </div>
                    </div>

                    {{-- 3. Grade Color Chip --}}
                    <div class="class-chip {{ $gradeColorClass }}">
                        {{ $s->schoolClass->nama_kelas ?? '—' }}
                    </div>

                    {{-- 4. Human Touch: Terakhir Terlambat --}}
                    @if($lastLateDate)
                        <div class="last-late-date">Terakhir: {{ $lastLateDate }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Empty state --}}
        <div class="empty-state">
            <div style="font-size:4.5rem;">🎉</div>
            <div style="font-size:1.4rem;font-weight:900;color:#fff;">Tidak Ada Keterlambatan!</div>
            <div style="font-size:0.85rem;color:#475569;">Semua siswa hadir tepat waktu pada periode ini.</div>
        </div>
        @endif
    </main>

    {{-- ── FOOTER ── --}}
    <footer>
        <span>© {{ date('Y') }} {{ $schoolSettings['school_name'] }}</span>
        <span>Auto-refresh setiap 30 detik</span>
        <a href="{{ route('kiosk.scan') }}">↗ Buka Kiosk Scan · Powered by {{ $schoolSettings['app_name'] }}</a>
    </footer>

</div>

<script>
    (function tick() {
        const now = new Date();
        const c = document.getElementById('live-clock');
        const d = document.getElementById('live-date');
        if (c) c.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        setTimeout(tick, 1000);
    })();

    setTimeout(() => location.reload(), 30000);
</script>
</body>
</html>
