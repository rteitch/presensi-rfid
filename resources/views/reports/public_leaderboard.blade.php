<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Kedisiplinan Siswa — {{ $schoolSettings['school_name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold:   #f59e0b;
            --silver: #94a3b8;
            --bronze: #b45309;
        }
        * { box-sizing: border-box; }
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #020617;
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* ── Animated cosmic background ── */
        .bg-cosmos {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse 120% 80% at 50% -20%, rgba(99,102,241,0.28) 0%, transparent 60%),
                        radial-gradient(ellipse 80% 60% at 100% 80%, rgba(245,158,11,0.12) 0%, transparent 50%),
                        radial-gradient(ellipse 60% 50% at 0% 80%, rgba(139,92,246,0.12) 0%, transparent 50%),
                        #020617;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Layout ── */
        .page-wrap { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Header ── */
        .site-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky; top: 0; z-index: 50;
        }
        .site-header .school-info { display: flex; align-items: center; gap: 1rem; }
        .site-header .logo { height: 3rem; width: auto; object-fit: contain; }
        .site-header h1 { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.02em; margin: 0; }
        .site-header .subtitle { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--gold); font-weight: 700; margin: 0; }

        .live-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.3rem 0.75rem; border-radius: 9999px;
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
            color: #34d399; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        }
        .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; animation: pulse-dot 1.5s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }

        .clock-block { text-align: right; }
        .clock-time { font-family: 'Plus Jakarta Sans', monospace; font-size: 1.5rem; font-weight: 900; color: var(--gold); letter-spacing: 0.04em; line-height: 1; }
        .clock-date { font-size: 0.65rem; color: #64748b; margin-top: 0.1rem; }

        /* ── Period badge ── */
        .period-pill {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; border-radius: 9999px;
            background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);
            color: var(--gold); font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        }

        /* ── Section title ── */
        .section-eyebrow { font-size: 0.62rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: var(--gold); margin-bottom: 0.25rem; }
        .section-title { font-size: clamp(1.5rem, 3vw, 2.4rem); font-weight: 900; letter-spacing: -0.03em; margin: 0 0 0.25rem; background: linear-gradient(135deg, #fff 40%, #fde68a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .section-sub { font-size: 0.78rem; color: #64748b; margin: 0; }

        /* ── Podium ── */
        .podium-wrap {
            display: flex; justify-content: center; align-items: flex-end; gap: 1.25rem;
            padding: 1rem 1rem 0;
        }

        .podium-card {
            border-radius: 1.5rem; padding: 2rem 1.5rem 1.5rem;
            display: flex; flex-direction: column; align-items: center;
            position: relative; overflow: hidden;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s;
            flex: 1; max-width: 280px;
        }
        .podium-card:hover { transform: translateY(-6px) scale(1.01); }

        /* Gold card */
        .podium-card.gold {
            background: linear-gradient(160deg, rgba(245,158,11,0.18) 0%, rgba(120,53,15,0.12) 100%);
            border: 1px solid rgba(245,158,11,0.4);
            box-shadow: 0 0 60px rgba(245,158,11,0.15), 0 20px 60px rgba(0,0,0,0.5);
            max-width: 320px;
            padding: 2.5rem 2rem 2rem;
        }
        /* Silver card */
        .podium-card.silver {
            background: linear-gradient(160deg, rgba(148,163,184,0.12) 0%, rgba(30,41,59,0.6) 100%);
            border: 1px solid rgba(148,163,184,0.25);
            box-shadow: 0 0 40px rgba(148,163,184,0.08), 0 15px 40px rgba(0,0,0,0.4);
        }
        /* Bronze card */
        .podium-card.bronze {
            background: linear-gradient(160deg, rgba(180,83,9,0.14) 0%, rgba(30,41,59,0.6) 100%);
            border: 1px solid rgba(180,83,9,0.3);
            box-shadow: 0 0 40px rgba(180,83,9,0.08), 0 15px 40px rgba(0,0,0,0.4);
        }

        .podium-glow { position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none; }
        .gold .podium-glow  { width: 160px; height: 160px; top: -40px; left: 50%; transform: translateX(-50%); background: rgba(245,158,11,0.3); }
        .silver .podium-glow { width: 120px; height: 120px; top: -30px; left: 50%; transform: translateX(-50%); background: rgba(148,163,184,0.2); }
        .bronze .podium-glow { width: 120px; height: 120px; top: -30px; left: 50%; transform: translateX(-50%); background: rgba(180,83,9,0.2); }

        .rank-crown { font-size: 2rem; margin-bottom: 0.5rem; }
        .gold .rank-crown  { font-size: 2.5rem; filter: drop-shadow(0 0 12px rgba(245,158,11,0.6)); }

        /* Avatar ring */
        .avatar-wrap { position: relative; margin-bottom: 1rem; }
        .avatar-img {
            border-radius: 50%; object-fit: cover; display: block;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        }
        .gold .avatar-img  { width: 110px; height: 110px; border: 4px solid var(--gold); box-shadow: 0 0 0 8px rgba(245,158,11,0.15), 0 8px 32px rgba(0,0,0,0.5); }
        .silver .avatar-img { width: 88px;  height: 88px;  border: 3px solid var(--silver); }
        .bronze .avatar-img { width: 88px;  height: 88px;  border: 3px solid var(--bronze); }

        .rank-badge {
            position: absolute; bottom: -4px; right: -4px;
            width: 28px; height: 28px; border-radius: 50%; font-size: 0.7rem; font-weight: 900;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #020617;
        }
        .gold   .rank-badge { background: var(--gold);   color: #422006; font-size: 0.8rem; width: 32px; height: 32px; }
        .silver .rank-badge { background: var(--silver); color: #0f172a; }
        .bronze .rank-badge { background: var(--bronze); color: #fff; }

        .podium-name { font-size: 1.05rem; font-weight: 800; text-align: center; letter-spacing: -0.02em; color: #fff; line-height: 1.25; margin-bottom: 0.25rem; }
        .gold .podium-name  { font-size: 1.2rem; }
        .podium-class { font-size: 0.7rem; font-weight: 700; text-align: center; letter-spacing: 0.06em; text-transform: uppercase; }
        .gold .podium-class   { color: #fcd34d; }
        .silver .podium-class { color: #94a3b8; }
        .bronze .podium-class { color: #d97706; }

        .podium-count-wrap { margin-top: 1.25rem; text-align: center; }
        .podium-count { font-size: 3rem; font-weight: 900; letter-spacing: -0.04em; line-height: 1; }
        .gold .podium-count   { font-size: 3.5rem; color: var(--gold); text-shadow: 0 0 30px rgba(245,158,11,0.5); }
        .silver .podium-count { color: #cbd5e1; }
        .bronze .podium-count { color: #d97706; }
        .podium-count-label { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #475569; margin-top: 0.15rem; }
        .gold .podium-count-label { color: rgba(252,211,77,0.6); }

        /* Platform steps */
        .podium-platform {
            margin-top: 1.25rem; width: 100%; border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
            padding: 0.5rem;
        }
        .gold   .podium-platform { background: rgba(245,158,11,0.15); color: var(--gold);   border: 1px solid rgba(245,158,11,0.2); height: 48px; }
        .silver .podium-platform { background: rgba(148,163,184,0.1); color: var(--silver); border: 1px solid rgba(148,163,184,0.15); height: 40px; }
        .bronze .podium-platform { background: rgba(180,83,9,0.1);   color: var(--bronze); border: 1px solid rgba(180,83,9,0.15); height: 36px; }

        /* ── Divider ── */
        .fancy-divider {
            display: flex; align-items: center; gap: 1rem; margin: 0.5rem 0;
        }
        .fancy-divider::before, .fancy-divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, rgba(245,158,11,0.3), transparent); }

        /* ── Rankings table ── */
        .rank-table-wrap {
            background: rgba(15,23,42,0.6);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1.25rem;
            overflow: hidden;
            backdrop-filter: blur(12px);
        }
        .rank-table { width: 100%; border-collapse: collapse; }
        .rank-table thead tr {
            background: rgba(2,6,23,0.6);
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .rank-table thead th {
            padding: 1rem 1.5rem; text-align: left;
            font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #475569;
        }
        .rank-table thead th.center { text-align: center; }
        .rank-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        .rank-table tbody tr:last-child { border-bottom: none; }
        .rank-table tbody tr:hover { background: rgba(255,255,255,0.03); }
        .rank-table tbody tr.row-gold   { background: rgba(245,158,11,0.05); }
        .rank-table tbody tr.row-silver { background: rgba(148,163,184,0.04); }
        .rank-table tbody tr.row-bronze { background: rgba(180,83,9,0.04); }
        .rank-table td { padding: 0.85rem 1.5rem; vertical-align: middle; }
        .rank-table td.center { text-align: center; }

        .row-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.1); }
        .row-gold   .row-avatar { border-color: var(--gold); }
        .row-silver .row-avatar { border-color: var(--silver); }
        .row-bronze .row-avatar { border-color: var(--bronze); }

        .row-name { font-size: 0.88rem; font-weight: 700; color: #f1f5f9; }
        .row-nis  { font-size: 0.65rem; color: #475569; font-weight: 600; }

        .class-tag {
            display: inline-block; padding: 0.2rem 0.6rem; border-radius: 0.4rem;
            background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2);
            color: #a5b4fc; font-size: 0.65rem; font-weight: 700;
        }

        .late-count { font-size: 1rem; font-weight: 900; color: var(--gold); }
        .hadir-count { font-size: 0.85rem; font-weight: 700; color: #34d399; }

        /* Bar progress */
        .bar-track { background: rgba(255,255,255,0.06); border-radius: 9999px; height: 6px; overflow: hidden; }
        .bar-fill  { height: 6px; border-radius: 9999px; transition: width 1s ease; }
        .bar-high   { background: linear-gradient(90deg, #ef4444, #f97316); }
        .bar-medium { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
        .bar-low    { background: linear-gradient(90deg, #22c55e, #34d399); }

        /* rank number chips */
        .rank-chip {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 50%;
            font-size: 0.72rem; font-weight: 900;
            background: rgba(255,255,255,0.06); color: #64748b; border: 1px solid rgba(255,255,255,0.08);
        }
        .rank-chip.g { background: rgba(245,158,11,0.2); color: var(--gold); border-color: rgba(245,158,11,0.3); }
        .rank-chip.s { background: rgba(148,163,184,0.18); color: var(--silver); border-color: rgba(148,163,184,0.25); }
        .rank-chip.b { background: rgba(180,83,9,0.18); color: #d97706; border-color: rgba(180,83,9,0.25); }

        /* empty state */
        .empty-card {
            background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1.5rem; padding: 5rem; text-align: center;
        }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;
            background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 1rem; padding: 0.75rem 1rem;
        }
        .filter-input {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            color: #f1f5f9; font-size: 0.78rem; border-radius: 0.6rem;
            padding: 0.45rem 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none; transition: border-color 0.2s;
        }
        .filter-input:focus { border-color: var(--gold); }
        .filter-input option { background: #1e293b; }
        .filter-btn {
            background: var(--gold); color: #1c0a00; font-size: 0.72rem; font-weight: 800;
            padding: 0.45rem 1.1rem; border-radius: 0.6rem; border: none; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif; transition: opacity 0.15s;
        }
        .filter-btn:hover { opacity: 0.85; }
        .reset-btn {
            background: rgba(255,255,255,0.06); color: #94a3b8; font-size: 0.72rem; font-weight: 700;
            padding: 0.45rem 0.9rem; border-radius: 0.6rem; border: 1px solid rgba(255,255,255,0.08);
            cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; text-decoration: none;
        }

        /* ── Scroll bar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }

        /* ── Footer ── */
        .site-footer {
            padding: 1rem 2rem; border-top: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: space-between;
            font-size: 0.65rem; color: #334155;
        }
    </style>
</head>
<body>
<div class="bg-cosmos"></div>
<div class="bg-grid"></div>

<div class="page-wrap">

    {{-- ── Header ── --}}
    <header class="site-header">
        <div class="school-info">
            @if($schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="logo">
            @else
                <div style="width:42px;height:42px;border-radius:0.75rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(99,102,241,0.4)">
                    <svg style="width:20px;height:20px;color:white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            @endif
            <div>
                <p class="subtitle">⚡ Papan Kedisiplinan Siswa</p>
                <h1>{{ $schoolSettings['school_name'] }}</h1>
            </div>
        </div>

        <div class="live-badge">
            <span class="live-dot"></span>
            Live · Auto Refresh
        </div>

        <div class="clock-block">
            <div class="clock-time" id="live-clock">00:00:00</div>
            <div class="clock-date" id="live-date">—</div>
        </div>
    </header>

    {{-- ── Main ── --}}
    <main style="flex:1;padding:1.5rem 2rem 2rem;max-width:1400px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:1.5rem;">

        {{-- Title row --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p class="section-eyebrow">🏆 Peringkat Keterlambatan</p>
                <h2 class="section-title">Hall of Shame — Sering Terlambat</h2>
                <p class="section-sub">Statistik real-time kedisiplinan waktu kehadiran seluruh siswa aktif.</p>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;">
                @if($bulan)
                    <div class="period-pill">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}
                    </div>
                @else
                    <div class="period-pill">Semua Waktu (All-Time)</div>
                @endif

                {{-- Filter --}}
                <form method="GET" action="{{ route('public.leaderboard') }}" class="filter-bar">
                    <input type="month" name="bulan" value="{{ $bulan }}" class="filter-input" placeholder="Pilih Bulan">
                    <select name="class_id" class="filter-input">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="filter-btn">Filter</button>
                    @if($bulan || $classId)
                        <a href="{{ route('public.leaderboard') }}" class="reset-btn">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        @if($students->isNotEmpty())

        {{-- ── PODIUM TOP 3 ── --}}
        <div class="podium-wrap">

            {{-- #2 Silver --}}
            @if($students->count() >= 2)
            <div class="podium-card silver" style="order:1">
                <div class="podium-glow"></div>
                <span class="rank-crown">🥈</span>
                <div class="avatar-wrap">
                    <img src="{{ $students[1]->foto_url }}" alt="{{ $students[1]->nama }}" class="avatar-img">
                    <span class="rank-badge">2</span>
                </div>
                <div class="podium-name">{{ $students[1]->nama }}</div>
                <div class="podium-class">{{ $students[1]->schoolClass->nama_kelas ?? '—' }}</div>
                <div class="podium-count-wrap">
                    <div class="podium-count">{{ $students[1]->total_terlambat }}</div>
                    <div class="podium-count-label">× Terlambat</div>
                </div>
                <div class="podium-platform">🥈 Peringkat 2</div>
            </div>
            @endif

            {{-- #1 Gold (center, tallest) --}}
            <div class="podium-card gold" style="order:0">
                <div class="podium-glow"></div>
                <span class="rank-crown">👑</span>
                <div class="avatar-wrap">
                    <img src="{{ $students[0]->foto_url }}" alt="{{ $students[0]->nama }}" class="avatar-img">
                    <span class="rank-badge">#1</span>
                </div>
                <div class="podium-name">{{ $students[0]->nama }}</div>
                <div class="podium-class">{{ $students[0]->schoolClass->nama_kelas ?? '—' }}</div>
                <div class="podium-count-wrap">
                    <div class="podium-count">{{ $students[0]->total_terlambat }}</div>
                    <div class="podium-count-label">× Terlambat Terbanyak</div>
                </div>
                <div class="podium-platform">👑 Juara Terlambat</div>
            </div>

            {{-- #3 Bronze --}}
            @if($students->count() >= 3)
            <div class="podium-card bronze" style="order:2">
                <div class="podium-glow"></div>
                <span class="rank-crown">🥉</span>
                <div class="avatar-wrap">
                    <img src="{{ $students[2]->foto_url }}" alt="{{ $students[2]->nama }}" class="avatar-img">
                    <span class="rank-badge">3</span>
                </div>
                <div class="podium-name">{{ $students[2]->nama }}</div>
                <div class="podium-class">{{ $students[2]->schoolClass->nama_kelas ?? '—' }}</div>
                <div class="podium-count-wrap">
                    <div class="podium-count">{{ $students[2]->total_terlambat }}</div>
                    <div class="podium-count-label">× Terlambat</div>
                </div>
                <div class="podium-platform">🥉 Peringkat 3</div>
            </div>
            @endif

        </div>

        {{-- Fancy divider --}}
        <div class="fancy-divider">
            <span style="font-size:0.62rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#475569;white-space:nowrap;">Peringkat Lengkap</span>
        </div>

        {{-- ── RANKINGS TABLE ── --}}
        <div class="rank-table-wrap">
            <table class="rank-table">
                <thead>
                    <tr>
                        <th class="center" style="width:64px">Rank</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th class="center">Terlambat</th>
                        <th class="center">Hadir</th>
                        <th style="width:200px">Tingkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $s)
                    @php
                        $rankNum = $index + 1;
                        $maxT    = $students->max('total_terlambat') ?: 1;
                        $pct     = round(($s->total_terlambat / $maxT) * 100);
                        $rowCls  = $rankNum === 1 ? 'row-gold' : ($rankNum === 2 ? 'row-silver' : ($rankNum === 3 ? 'row-bronze' : ''));
                        $chipCls = $rankNum === 1 ? 'g' : ($rankNum === 2 ? 's' : ($rankNum === 3 ? 'b' : ''));
                        $barCls  = $pct >= 70 ? 'bar-high' : ($pct >= 35 ? 'bar-medium' : 'bar-low');
                    @endphp
                    <tr class="{{ $rowCls }}">
                        {{-- Rank --}}
                        <td class="center">
                            @if($rankNum <= 3)
                                <span class="rank-chip {{ $chipCls }}">
                                    {{ $rankNum === 1 ? '🥇' : ($rankNum === 2 ? '🥈' : '🥉') }}
                                </span>
                            @else
                                <span class="rank-chip">{{ $rankNum }}</span>
                            @endif
                        </td>

                        {{-- Siswa --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:0.85rem;">
                                <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="row-avatar">
                                <div>
                                    <div class="row-name">{{ $s->nama }}</div>
                                    <div class="row-nis">NIS {{ substr($s->nis ?? '------', 0, 3) }}***</div>
                                </div>
                            </div>
                        </td>

                        {{-- Kelas --}}
                        <td><span class="class-tag">{{ $s->schoolClass->nama_kelas ?? '—' }}</span></td>

                        {{-- Terlambat --}}
                        <td class="center"><span class="late-count">{{ $s->total_terlambat }}×</span></td>

                        {{-- Hadir --}}
                        <td class="center"><span class="hadir-count">{{ $s->total_hadir }}</span></td>

                        {{-- Bar --}}
                        <td>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <div class="bar-track" style="flex:1">
                                    <div class="bar-fill {{ $barCls }}" style="width:{{ max($pct,3) }}%"></div>
                                </div>
                                <span style="font-size:0.65rem;font-weight:700;color:#475569;white-space:nowrap;font-family:monospace;min-width:32px;text-align:right;">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else
        {{-- Empty State --}}
        <div class="empty-card">
            <div style="font-size:4rem;margin-bottom:1rem;">🎉</div>
            <h3 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0 0 0.5rem;">Tidak Ada Data Keterlambatan</h3>
            <p style="color:#475569;font-size:0.85rem;margin:0">Luar biasa! Semua siswa hadir tepat waktu pada periode ini.</p>
        </div>
        @endif

    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <span>© {{ date('Y') }} {{ $schoolSettings['school_name'] }}</span>
        <a href="{{ route('kiosk.scan') }}" style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.3rem 0.75rem;border-radius:0.5rem;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);color:#a5b4fc;font-size:0.65rem;font-weight:700;text-decoration:none;transition:background 0.15s" onmouseover="this.style.background='rgba(99,102,241,0.25)'" onmouseout="this.style.background='rgba(99,102,241,0.15)'">
            <svg style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Buka Kiosk Scan
        </a>
        <span>Powered by {{ $schoolSettings['app_name'] }}</span>
    </footer>

</div>

<script>
    // Live clock
    function tick() {
        const now  = new Date();
        const cl   = document.getElementById('live-clock');
        const dt   = document.getElementById('live-date');
        if (cl) cl.textContent = now.toLocaleTimeString('id-ID', {hour12: false});
        if (dt) dt.textContent = now.toLocaleDateString('id-ID', {weekday:'long', year:'numeric', month:'long', day:'numeric'});
    }
    setInterval(tick, 1000); tick();

    // Animate progress bars on load
    document.querySelectorAll('.bar-fill').forEach(el => {
        const w = el.style.width;
        el.style.width = '0%';
        setTimeout(() => { el.style.width = w; }, 300);
    });

    // Auto-refresh every 30 seconds for hall-display mode
    setTimeout(() => location.reload(), 30000);
</script>
</body>
</html>
