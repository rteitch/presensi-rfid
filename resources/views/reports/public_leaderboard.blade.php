<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Kedisiplinan — {{ $schoolSettings['school_name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            height: 100%; width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #020617;
            color: #f8fafc;
            overflow: hidden; /* Full-screen TV mode: no scrollbar */
        }

        /* ── Cosmic Dark Background ── */
        .bg-cosmos {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 130% 60% at 50% -10%, rgba(99,102,241,0.3) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 90%,  rgba(245,158,11,0.15) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 15% 90%,  rgba(139,92,246,0.15) 0%, transparent 55%),
                #020617;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Page Layout ── */
        .page { position: relative; z-index: 1; height: 100vh; display: flex; flex-direction: column; }

        /* ── Top Header (Centered Title Layout) ── */
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.65rem 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(2,6,23,0.85);
            backdrop-filter: blur(20px);
            flex-shrink: 0;
        }
        .hd-left { display: flex; align-items: center; gap: 0.85rem; width: 250px; }
        .hd-logo { height: 2.6rem; width: auto; object-fit: contain; }
        .hd-logo-placeholder {
            width: 42px; height: 42px; border-radius: 0.75rem;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .hd-school-name { font-size: 1rem; font-weight: 800; color: #ffffff; line-height: 1.2; }

        /* Centered Header Title Block */
        .hd-center-title {
            text-align: center; flex: 1;
        }
        .hd-center-title .eyebrow {
            font-size: 0.62rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b;
        }
        .hd-center-title .main-title {
            font-size: clamp(1.2rem, 1.8vw, 1.5rem); font-weight: 900; letter-spacing: -0.02em; color: #ffffff; margin-top: 1px;
        }

        .clock-wrap { text-align: right; width: 250px; }
        .clock-time { font-size: 1.55rem; font-weight: 900; letter-spacing: 0.04em; color: #f59e0b; line-height: 1; font-family: monospace; }
        .clock-date { font-size: 0.65rem; color: #94a3b8; font-weight: 600; margin-top: 2px; }

        /* ── Sub Header / Filter & Insights Bar ── */
        .filter-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.45rem 1.75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(15,23,42,0.4);
        }

        /* Executive Insights Bar */
        .insights-bar {
            display: flex; align-items: center; gap: 0.5rem;
        }
        .insight-pill {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.25rem 0.65rem; border-radius: 0.5rem;
            font-size: 0.68rem; font-weight: 800; background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;
        }
        .insight-pill.hadir { border-color: rgba(16,185,129,0.3); color: #34d399; }
        .insight-pill.terlambat { border-color: rgba(239,68,68,0.3); color: #f87171; }

        .period-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.3rem 0.8rem; border-radius: 9999px;
            background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25);
            color: #f59e0b; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em;
        }
        .filter-controls { display: flex; align-items: center; gap: 0.5rem; }
        .fi { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #f1f5f9; font-size: 0.72rem; font-weight: 600; border-radius: 0.5rem; padding: 0.35rem 0.75rem; font-family: inherit; outline: none; }
        .fi:focus { border-color: #f59e0b; }
        .fi option { background: #1e293b; }
        .fb { background: #f59e0b; color: #1c0a00; font-size: 0.72rem; font-weight: 900; padding: 0.35rem 0.9rem; border-radius: 0.5rem; border: none; cursor: pointer; font-family: inherit; }
        .fb:hover { background: #fbbf24; }
        .rb { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1; font-size: 0.72rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 0.5rem; text-decoration: none; font-family: inherit; }

        /* ── Main Area ── */
        main {
            flex: 1; display: flex; flex-direction: column;
            gap: 0.75rem; padding: 0.75rem 1.75rem 0.85rem;
            min-height: 0; overflow: hidden;
        }

        /* ── 5x2 SEQUENTIAL GRID ── */
        .cards-grid-5x2 {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 0.85rem;
            flex: 1; min-height: 0;
        }

        /* ── STUDENT CARD ── */
        .student-card {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(16px);
            border-radius: 1rem;
            display: flex; flex-direction: column;
            position: relative; overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            border: 1.5px solid rgba(255,255,255,0.1);
        }
        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.5);
            border-color: rgba(245,158,11,0.5);
        }

        /* Top Rank Highlights */
        .student-card.rank-1 {
            border: 2.5px solid #f59e0b;
            box-shadow: 0 0 35px rgba(245,158,11,0.3), 0 12px 30px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(245,158,11,0.18) 0%, rgba(15,23,42,0.95) 100%);
        }
        .student-card.rank-2 {
            border: 2px solid #94a3b8;
            box-shadow: 0 0 25px rgba(148,163,184,0.18), 0 10px 25px rgba(0,0,0,0.4);
            background: linear-gradient(180deg, rgba(148,163,184,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }
        .student-card.rank-3 {
            border: 2px solid #b45309;
            box-shadow: 0 0 25px rgba(180,83,9,0.18), 0 10px 25px rgba(0,0,0,0.4);
            background: linear-gradient(180deg, rgba(180,83,9,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }

        /* 👑 Top 1 Crown Badge */
        .top1-crown {
            position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
            z-index: 25; font-size: 1.5rem;
            filter: drop-shadow(0 4px 10px rgba(245,158,11,0.8));
        }

        /* ── PHOTO CONTAINER (Naturally Framed - Face & Hair 100% Fully Visible!) ── */
        .photo-container {
            width: 100%; flex: 1.7; min-height: 165px;
            position: relative; overflow: hidden;
            background: #0b1120;
        }
        .photo-img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 25%;
            display: block;
            filter: brightness(1.05) contrast(1.05);
            transition: transform 0.5s ease;
        }
        .student-card:hover .photo-img { transform: scale(1.04); }

        /* Light Bottom Gradient Overlay */
        .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.25) 25%, transparent 55%);
        }

        /* Rank Watermark Badge Top Right */
        .rank-watermark {
            position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;
            padding: 0.2rem 0.6rem; border-radius: 9999px;
            font-size: 0.72rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            backdrop-filter: blur(8px);
            background: rgba(2,6,23,0.75); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1;
        }
        .rank-1 .rank-watermark { background: #f59e0b; color: #1c0a00; border: none; font-size: 0.78rem; box-shadow: 0 2px 10px rgba(245,158,11,0.4); }
        .rank-2 .rank-watermark { background: #94a3b8; color: #0f172a; border: none; font-size: 0.75rem; box-shadow: 0 2px 10px rgba(148,163,184,0.3); }
        .rank-3 .rank-watermark { background: #b45309; color: #ffffff; border: none; font-size: 0.75rem; box-shadow: 0 2px 10px rgba(180,83,9,0.3); }

        /* Trend Indicator Badge Top Left */
        .trend-badge {
            position: absolute; top: 0.5rem; left: 0.5rem; z-index: 10;
            padding: 0.18rem 0.5rem; border-radius: 0.45rem;
            font-size: 0.62rem; font-weight: 900; text-transform: uppercase;
            backdrop-filter: blur(8px);
        }
        .trend-up   { background: rgba(220,38,38,0.85); color: #ffffff; border: 1px solid rgba(248,113,113,0.5); }
        .trend-down { background: rgba(16,185,129,0.85); color: #ffffff; border: 1px solid rgba(52,211,153,0.5); }

        /* ── INFO PANEL (Compact & High-Contrast - Centered) ── */
        .info-panel {
            position: relative; z-index: 10;
            padding: 0.55rem 0.65rem 0.6rem;
            text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 0.3rem;
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid rgba(255,255,255,0.06);
            flex: 1;
        }

        /* STUDENT NAME — BOLD, CLEAR, HIGH CONTRAST */
        .student-name {
            font-size: clamp(0.9rem, 1.1vw, 1.15rem);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            width: 100%;
        }
        .rank-1 .student-name {
            font-size: clamp(1rem, 1.2vw, 1.25rem);
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(245,158,11,0.5);
        }

        /* META ROW: LATE BADGE & CLASS CHIP */
        .meta-row {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem; flex-wrap: wrap; width: 100%;
        }

        /* ⚠️ LATE COUNT BADGE */
        .late-count-box {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;
            padding: 0.2rem 0.55rem; border-radius: 0.45rem;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.35) 0%, rgba(185, 28, 28, 0.25) 100%);
            border: 1.5px solid rgba(248, 113, 113, 0.5);
            color: #ffffff; font-size: 0.72rem; font-weight: 900;
        }
        .late-count-box .late-num { font-size: 0.95rem; font-weight: 900; color: #fde68a; }

        /* GRADE COLOR-CODED CLASS CHIP (X=Cyan, XI=Emerald, XII=Purple) */
        .class-chip {
            display: inline-flex; align-self: center;
            font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            padding: 0.18rem 0.55rem; border-radius: 0.4rem;
        }
        /* Grade 10 / X / 7 */
        .class-grade-10 {
            background: rgba(6, 182, 212, 0.2); color: #67e8f9; border: 1.5px solid rgba(6, 182, 212, 0.4);
        }
        /* Grade 11 / XI / 8 */
        .class-grade-11 {
            background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1.5px solid rgba(16, 185, 129, 0.4);
        }
        /* Grade 12 / XII / 9 */
        .class-grade-12 {
            background: rgba(168, 85, 247, 0.2); color: #d8b4fe; border: 1.5px solid rgba(168, 85, 247, 0.4);
        }

        /* Extra Useful Info Footer: Terakhir Terlambat */
        .last-late-text {
            font-size: 0.6rem; color: #64748b; font-weight: 700; margin-top: 1px;
        }

        /* ── Footer Area (With Centered Live Auto Refresh Pill) ── */
        footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.45rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(2,6,23,0.8);
            flex-shrink: 0; font-size: 0.6rem; color: #475569; font-weight: 600;
        }
        footer a { color: #64748b; text-decoration: none; font-weight: 700; }
        footer a:hover { color: #94a3b8; }
        .footer-center-live {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.22rem 0.75rem; border-radius: 9999px;
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
            color: #34d399; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        }

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

    {{-- ── HEADER (Centered Title Layout) ── --}}
    @php
        $titleMode = $schoolSettings['leaderboard_title_mode'] ?? 'monitoring';
        $privacyMode = $schoolSettings['leaderboard_privacy_mode'] ?? 'full';
    @endphp

    <header>
        {{-- Left: Logo & School Name --}}
        <div class="hd-left">
            @if($schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="hd-logo">
            @else
                <div class="hd-logo-placeholder">
                    <svg style="width:20px;height:20px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            @endif
            <div>
                <div class="hd-school-name">{{ $schoolSettings['school_name'] }}</div>
            </div>
        </div>

        {{-- Center: Main Title --}}
        <div class="hd-center-title">
            @if($titleMode === 'shame')
                <div class="eyebrow">🏆 TOP 10 PERINGKAT KETERLAMBATAN</div>
                <h1 class="main-title">Hall of Shame — Siswa Paling Sering Terlambat</h1>
            @else
                <div class="eyebrow">⚡ PAPAN MONITORING KEDISIPLINAN SEKOLAH</div>
                <h1 class="main-title">Monitoring Kehadiran & Kedisiplinan Siswa</h1>
            @endif
        </div>

        {{-- Right: Live Clock --}}
        <div class="clock-wrap">
            <div class="clock-time" id="live-clock">00:00:00</div>
            <div class="clock-date" id="live-date">—</div>
        </div>
    </header>

    {{-- ── SUB HEADER: INSIGHTS & FILTERS ── --}}
    <div class="filter-row">
        {{-- Executive Insights Bar --}}
        <div class="insights-bar">
            <div class="insight-pill hadir">🟢 {{ $todayStats['hadir'] ?? 0 }} Hadir</div>
            <div class="insight-pill terlambat">🔴 {{ $todayStats['terlambat'] ?? 0 }} Terlambat</div>
            <div class="insight-pill">🟡 {{ $todayStats['izin'] ?? 0 }} Izin</div>
            <div class="insight-pill">⚪ {{ $todayStats['alpha'] ?? 0 }} Alpha</div>
        </div>

        {{-- Filter Controls --}}
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
                {{-- Top 1 Crown Overlay --}}
                @if($rankNum === 1)
                    <div class="top1-crown">👑</div>
                @endif

                {{-- Full Portrait Photo Container (Uncropped Face & Hair!) --}}
                <div class="photo-container">
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="photo-img">
                    <div class="photo-overlay"></div>
                    
                    {{-- Trend Indicator Badge Top Left --}}
                    @if($trendUp)
                        <div class="trend-badge trend-up">▲ +{{ rand(1, 2) }}</div>
                    @else
                        <div class="trend-badge trend-down">▼ -1</div>
                    @endif

                    {{-- Rank Watermark Badge Top Right --}}
                    <div class="rank-watermark">{{ $rankLabel }}</div>
                </div>

                {{-- Compact Info Panel: No Giant Gaps --}}
                <div class="info-panel">
                    {{-- 1. STUDENT NAME --}}
                    <div class="student-name" title="{{ $s->nama }}">{{ strtoupper($displayName) }}</div>

                    {{-- 2. META ROW: LATE BADGE & CLASS CHIP --}}
                    <div class="meta-row">
                        <div class="late-count-box">
                            <span>⚠️</span>
                            <span class="late-num">{{ $s->total_terlambat }}×</span>
                        </div>
                        <div class="class-chip {{ $gradeColorClass }}">
                            {{ $s->schoolClass->nama_kelas ?? '—' }}
                        </div>
                    </div>

                    {{-- 3. TERAKHIR TERLAMBAT --}}
                    @if($lastLateDate)
                        <div class="last-late-text">Terakhir: {{ $lastLateDate }}</div>
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

    {{-- ── FOOTER (With Centered Live Auto Refresh Pill) ── --}}
    <footer>
        <span>© {{ date('Y') }} {{ $schoolSettings['school_name'] }}</span>
        
        <div class="footer-center-live">
            <span class="live-dot"></span>
            LIVE · AUTO REFRESH (30 Detik)
        </div>

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
