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
        .hd-center-title { text-align: center; flex: 1; }
        .hd-center-title .eyebrow {
            font-size: 0.62rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b;
            display: flex; align-items: center; justify-content: center; gap: 0.35rem;
        }
        .hd-center-title .main-title {
            font-size: clamp(1.2rem, 1.8vw, 1.5rem); font-weight: 900; letter-spacing: -0.02em; color: #ffffff; margin-top: 1px;
        }

        /* Clock & Integrated Live Indicator */
        .clock-wrap { text-align: right; width: 250px; }
        .live-status-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.58rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em;
            color: #34d399; margin-bottom: 2px;
        }
        .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; animation: blink 1.4s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

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
        .insights-bar { display: flex; align-items: center; gap: 0.5rem; }
        .insight-pill {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.25rem 0.65rem; border-radius: 0.5rem;
            font-size: 0.68rem; font-weight: 800; background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;
        }
        .insight-pill.hadir { border-color: rgba(16,185,129,0.3); color: #34d399; }
        .insight-pill.terlambat { border-color: rgba(239,68,68,0.3); color: #f87171; }
        .dot-indicator { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

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

        /* ── STUDENT CARD (With Mac OS Glass Panel at Bottom) ── */
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

        /* Top 1 SVG Crown Badge */
        .top1-crown {
            position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
            z-index: 25; width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            background: #f59e0b; border-radius: 50%;
            box-shadow: 0 4px 12px rgba(245,158,11,0.6);
        }

        /* ── PHOTO CONTAINER (Full Card Background Frame) ── */
        .photo-container {
            width: 100%; height: 100%; min-height: 200px;
            position: relative; overflow: hidden;
            background: #090d16;
            display: flex; align-items: center; justify-content: center;
        }

        /* Ambient Blurred Background Image */
        .photo-bg-blur {
            position: absolute; inset: -15px;
            width: calc(100% + 30px); height: calc(100% + 30px);
            object-fit: cover; filter: blur(14px) brightness(0.45);
            z-index: 1; opacity: 0.65;
            transition: transform 0.5s ease;
        }

        /* 100% UNCROPPED MAIN PHOTO */
        .photo-img-full {
            position: relative; z-index: 2;
            max-height: 100%; max-width: 100%; height: 100%; width: 100%;
            object-fit: contain; /* GUARANTEES ZERO CROPPING OF FACES! */
            display: block;
            filter: brightness(1.06) contrast(1.06);
            transition: transform 0.5s ease;
            padding-bottom: 70px; /* Leave breathing room for Mac OS glass panel at bottom */
        }

        .student-card:hover .photo-img-full { transform: scale(1.04); }
        .student-card:hover .photo-bg-blur { transform: scale(1.08); }

        /* Rank Watermark Badge Top Right */
        .rank-watermark {
            position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;
            padding: 0.2rem 0.6rem; border-radius: 9999px;
            font-size: 0.72rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            backdrop-filter: blur(8px);
            background: rgba(2,6,23,0.8); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1;
            display: inline-flex; align-items: center; gap: 0.3rem;
        }
        .rank-1 .rank-watermark { background: #f59e0b; color: #1c0a00; border: none; font-size: 0.78rem; box-shadow: 0 2px 10px rgba(245,158,11,0.4); }
        .rank-2 .rank-watermark { background: #94a3b8; color: #0f172a; border: none; font-size: 0.75rem; box-shadow: 0 2px 10px rgba(148,163,184,0.3); }
        .rank-3 .rank-watermark { background: #b45309; color: #ffffff; border: none; font-size: 0.75rem; box-shadow: 0 2px 10px rgba(180,83,9,0.3); }

        /* Trend Indicator Badge Top Left */
        .trend-badge {
            position: absolute; top: 0.5rem; left: 0.5rem; z-index: 10;
            padding: 0.18rem 0.5rem; border-radius: 0.45rem;
            font-size: 0.62rem; font-weight: 900; text-transform: uppercase;
            backdrop-filter: blur(8px); display: inline-flex; align-items: center; gap: 0.2rem;
        }
        .trend-up   { background: rgba(220,38,38,0.85); color: #ffffff; border: 1px solid rgba(248,113,113,0.5); }
        .trend-down { background: rgba(16,185,129,0.85); color: #ffffff; border: 1px solid rgba(52,211,153,0.5); }

        /* ── MAC OS FROSTED GLASS INFO PANEL (Bottom Floating Dock) ── */
        .info-panel {
            position: absolute; bottom: 0; left: 0; right: 0; z-index: 20;
            padding: 0.5rem 0.65rem 0.55rem;
            text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 0.25rem;
            background: rgba(15, 23, 42, 0.65); /* Transparent Dark Glass */
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        }

        /* STUDENT NAME — BOLD, CLEAR, HIGH CONTRAST */
        .student-name {
            font-size: clamp(0.85rem, 1.05vw, 1.1rem);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            width: 100%;
            text-shadow: 0 2px 6px rgba(0,0,0,0.8);
        }
        .rank-1 .student-name {
            font-size: clamp(0.95rem, 1.15vw, 1.2rem);
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(245,158,11,0.5);
        }

        /* META ROW: LATE BADGE & CLASS CHIP */
        .meta-row {
            display: flex; align-items: center; justify-content: center; gap: 0.35rem; flex-wrap: wrap; width: 100%;
        }

        /* LATE COUNT BADGE */
        .late-count-box {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem;
            padding: 0.18rem 0.5rem; border-radius: 0.45rem;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.45) 0%, rgba(185, 28, 28, 0.3) 100%);
            border: 1.5px solid rgba(248, 113, 113, 0.6);
            color: #ffffff; font-size: 0.7rem; font-weight: 900;
            box-shadow: 0 2px 8px rgba(220,38,38,0.3);
        }
        .late-count-box .late-num { font-size: 0.9rem; font-weight: 900; color: #fde68a; }

        /* GRADE COLOR-CODED CLASS CHIP (X=Cyan, XI=Emerald, XII=Purple) */
        .class-chip {
            display: inline-flex; align-self: center;
            font-size: 0.62rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            padding: 0.16rem 0.5rem; border-radius: 0.4rem;
        }
        /* Grade 10 / X / 7 */
        .class-grade-10 {
            background: rgba(6, 182, 212, 0.25); color: #67e8f9; border: 1.5px solid rgba(6, 182, 212, 0.5);
        }
        /* Grade 11 / XI / 8 */
        .class-grade-11 {
            background: rgba(16, 185, 129, 0.25); color: #6ee7b7; border: 1.5px solid rgba(16, 185, 129, 0.5);
        }
        /* Grade 12 / XII / 9 */
        .class-grade-12 {
            background: rgba(168, 85, 247, 0.25); color: #d8b4fe; border: 1.5px solid rgba(168, 85, 247, 0.5);
        }

        /* Extra Useful Info Footer: Terakhir Terlambat */
        .last-late-text {
            font-size: 0.58rem; color: #cbd5e1; font-weight: 700; margin-top: 1px;
        }

        /* ── Clean Footer Area ── */
        footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.45rem 1.75rem; border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(2,6,23,0.85);
            flex-shrink: 0; font-size: 0.62rem; color: #64748b; font-weight: 600;
        }
        footer a { color: #94a3b8; text-decoration: none; font-weight: 700; }
        footer a:hover { color: #f8fafc; }

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
                <div class="eyebrow">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>TOP 10 PERINGKAT KETERLAMBATAN</span>
                </div>
                <h1 class="main-title">Hall of Shame — Siswa Paling Sering Terlambat</h1>
            @else
                <div class="eyebrow">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>PAPAN MONITORING KEDISIPLINAN SEKOLAH</span>
                </div>
                <h1 class="main-title">Monitoring Kehadiran & Kedisiplinan Siswa</h1>
            @endif
        </div>

        {{-- Right: Integrated Live Clock & Status --}}
        <div class="clock-wrap">
            <div class="live-status-chip">
                <span class="live-dot"></span>
                <span>Live · Auto Refresh (30s)</span>
            </div>
            <div class="clock-time" id="live-clock">00:00:00</div>
            <div class="clock-date" id="live-date">—</div>
        </div>
    </header>

    {{-- ── SUB HEADER: INSIGHTS & FILTERS ── --}}
    <div class="filter-row">
        {{-- Executive Insights Bar --}}
        <div class="insights-bar">
            <div class="insight-pill hadir">
                <span class="dot-indicator bg-emerald-400"></span>
                <span>{{ $todayStats['hadir'] ?? 0 }} Hadir</span>
            </div>
            <div class="insight-pill terlambat">
                <span class="dot-indicator bg-rose-500"></span>
                <span>{{ $todayStats['terlambat'] ?? 0 }} Terlambat</span>
            </div>
            <div class="insight-pill">
                <span class="dot-indicator bg-amber-400"></span>
                <span>{{ $todayStats['izin'] ?? 0 }} Izin</span>
            </div>
            <div class="insight-pill">
                <span class="dot-indicator bg-slate-400"></span>
                <span>{{ $todayStats['alpha'] ?? 0 }} Alpha</span>
            </div>
        </div>

        {{-- Filter Controls --}}
        <div style="display:flex;align-items:center;gap:0.6rem;">
            @if($bulan)
                <div class="period-chip">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</span>
                </div>
            @else
                <div class="period-chip">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Semua Waktu</span>
                </div>
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
                $rankLabel = sprintf('#%02d', $rankNum);

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
                {{-- Top 1 SVG Crown Badge --}}
                @if($rankNum === 1)
                    <div class="top1-crown">
                        <svg class="w-5 h-5 text-amber-950" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg>
                    </div>
                @endif

                {{-- Full Background Photo Container --}}
                <div class="photo-container">
                    {{-- Ambient Blurred Backdrop Image --}}
                    <img src="{{ $s->foto_url }}" alt="" class="photo-bg-blur">
                    
                    {{-- 100% Uncropped Main Photo --}}
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="photo-img-full">
                    
                    {{-- Trend Indicator Badge Top Left --}}
                    @if($trendUp)
                        <div class="trend-badge trend-up">
                            <svg class="w-3 h-3 text-white inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                            <span>+{{ rand(1, 2) }}</span>
                        </div>
                    @else
                        <div class="trend-badge trend-down">
                            <svg class="w-3 h-3 text-white inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            <span>-1</span>
                        </div>
                    @endif

                    {{-- Rank Watermark Badge Top Right --}}
                    <div class="rank-watermark">
                        @if($rankNum === 1)
                            <svg class="w-3.5 h-3.5 text-amber-950 shrink-0 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.4 2.3 7.2L12 16.5 5.7 21l2.3-7.2-6-4.4h7.6z"/></svg>
                        @elseif($rankNum === 2)
                            <svg class="w-3.5 h-3.5 text-slate-800 shrink-0 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.4 2.3 7.2L12 16.5 5.7 21l2.3-7.2-6-4.4h7.6z"/></svg>
                        @elseif($rankNum === 3)
                            <svg class="w-3.5 h-3.5 text-amber-100 shrink-0 inline" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.4 2.3 7.2L12 16.5 5.7 21l2.3-7.2-6-4.4h7.6z"/></svg>
                        @endif
                        <span>RANK {{ $rankLabel }}</span>
                    </div>
                </div>

                {{-- Mac OS Frosted Glass Info Panel Floating at Bottom --}}
                <div class="info-panel">
                    {{-- 1. STUDENT NAME --}}
                    <div class="student-name" title="{{ $s->nama }}">{{ strtoupper($displayName) }}</div>

                    {{-- 2. META ROW: LATE BADGE & CLASS CHIP --}}
                    <div class="meta-row">
                        <div class="late-count-box">
                            <svg class="w-3 h-3 text-amber-300 shrink-0 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
            <div class="w-20 h-20 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div style="font-size:1.4rem;font-weight:900;color:#fff;">Tidak Ada Keterlambatan!</div>
            <div style="font-size:0.85rem;color:#475569;">Semua siswa hadir tepat waktu pada periode ini.</div>
        </div>
        @endif
    </main>

    {{-- ── CLEAN FOOTER AREA ── --}}
    <footer>
        <span>© {{ date('Y') }} {{ $schoolSettings['school_name'] }}</span>
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
