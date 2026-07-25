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
            overflow: hidden; /* TV display: no scrollbar */
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

        /* ── Top Header ── */
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.6rem 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: rgba(2,6,23,0.85);
            backdrop-filter: blur(20px);
            flex-shrink: 0;
        }
        .hd-left { display: flex; align-items: center; gap: 0.85rem; }
        .hd-logo { height: 2.6rem; width: auto; object-fit: contain; }
        .hd-logo-placeholder {
            width: 42px; height: 42px; border-radius: 0.75rem;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .hd-school-name { font-size: 1rem; font-weight: 800; letter-spacing: -0.01em; color: #ffffff; }
        .hd-badge { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.12em; color: #f59e0b; font-weight: 900; }

        .live-pill {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.3rem 0.8rem; border-radius: 9999px;
            background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3);
            color: #34d399; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;
        }
        .live-dot { width: 7px; height: 7px; border-radius: 50%; background: #34d399; animation: blink 1.4s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .clock-wrap { text-align: right; }
        .clock-time { font-size: 1.5rem; font-weight: 900; letter-spacing: 0.04em; color: #f59e0b; line-height: 1; font-family: monospace; }
        .clock-date { font-size: 0.65rem; color: #94a3b8; font-weight: 600; margin-top: 2px; }

        /* ── Sub Header / Filter Row ── */
        .filter-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.55rem 1.75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(15,23,42,0.4);
        }
        .title-block .eyebrow { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b; font-weight: 900; }
        .title-block .main-title {
            font-size: clamp(1.2rem, 2.2vw, 1.55rem); font-weight: 900; letter-spacing: -0.03em;
            background: linear-gradient(90deg, #fff 30%, #fde68a 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            line-height: 1.1;
        }
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
            gap: 0.75rem; padding: 0.75rem 1.75rem;
            min-height: 0; overflow: hidden;
        }

        /* ── PODIUM ROW (Top 3 Cards) ── */
        .podium-row {
            display: flex; align-items: flex-end; justify-content: center; gap: 1.1rem;
            flex: 1.45; min-height: 0;
        }

        .podium-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border-radius: 1.25rem;
            padding: 0.9rem;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .podium-card:hover { transform: translateY(-4px); }

        /* Rank #1 Gold (Center, Taller) */
        .podium-card.gold {
            width: 330px; height: 100%;
            border: 2.5px solid #f59e0b;
            box-shadow: 0 0 40px rgba(245,158,11,0.3), 0 16px 40px rgba(0,0,0,0.6);
            background: linear-gradient(180deg, rgba(245,158,11,0.15) 0%, rgba(15,23,42,0.95) 100%);
        }

        /* Rank #2 Silver (Left) */
        .podium-card.silver {
            width: 290px; height: 92%;
            border: 2px solid #94a3b8;
            box-shadow: 0 0 30px rgba(148,163,184,0.18), 0 12px 32px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(148,163,184,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }

        /* Rank #3 Bronze (Right) */
        .podium-card.bronze {
            width: 290px; height: 88%;
            border: 2px solid #b45309;
            box-shadow: 0 0 30px rgba(180,83,9,0.18), 0 12px 32px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(180,83,9,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }

        /* Top Tag Banner */
        .card-banner {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.55rem;
        }
        .banner-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.3rem 0.75rem; border-radius: 9999px;
            font-size: 0.72rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em;
        }
        .gold .banner-chip   { background: #f59e0b; color: #1c0a00; box-shadow: 0 2px 10px rgba(245,158,11,0.4); }
        .silver .banner-chip { background: #94a3b8; color: #0f172a; box-shadow: 0 2px 10px rgba(148,163,184,0.3); }
        .bronze .banner-chip { background: #b45309; color: #ffffff; box-shadow: 0 2px 10px rgba(180,83,9,0.3); }

        .late-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.3rem 0.75rem; border-radius: 0.55rem;
            background: rgba(2,6,23,0.8); border: 1px solid rgba(245,158,11,0.3);
            font-size: 0.9rem; font-weight: 900; color: #f59e0b;
        }
        .late-badge span { font-size: 0.58rem; color: #cbd5e1; font-weight: 800; text-transform: uppercase; }

        /* Dedicated Photo Container (Framed, High Precision Cropped) */
        .photo-frame {
            width: 100%; flex: 1; min-height: 110px;
            border-radius: 0.85rem; overflow: hidden;
            border: 1.5px solid rgba(255,255,255,0.15);
            position: relative; background: #0f172a;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
        }
        .photo-frame img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 20%;
            display: block;
        }

        /* Bottom Info Box - High Contrast & Prominent Typography */
        .card-details {
            margin-top: 0.65rem; text-align: center;
            background: rgba(2, 6, 23, 0.7);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            padding: 0.55rem 0.5rem;
        }
        .card-name {
            font-size: 1.15rem; font-weight: 900; color: #ffffff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            letter-spacing: -0.01em; line-height: 1.25;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
        .gold .card-name { font-size: 1.3rem; color: #ffffff; }

        .card-class {
            display: inline-block; margin-top: 0.35rem;
            font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em;
            padding: 0.25rem 0.75rem; border-radius: 0.45rem;
        }
        .gold   .card-class { background: rgba(245,158,11,0.25); color: #fde68a; border: 1px solid rgba(245,158,11,0.4); }
        .silver .card-class { background: rgba(148,163,184,0.25); color: #f1f5f9; border: 1px solid rgba(148,163,184,0.35); }
        .bronze .card-class { background: rgba(180,83,9,0.25); color: #fed7aa; border: 1px solid rgba(180,83,9,0.35); }

        /* ── RANK 4–10 GRID ── */
        .rank-grid {
            display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.65rem;
            flex: 1; min-height: 0;
        }

        .mini-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 0.9rem;
            padding: 0.55rem;
            display: flex; flex-direction: column; justify-content: space-between;
            transition: transform 0.25s ease, border-color 0.25s ease;
        }
        .mini-card:hover { transform: translateY(-3px); border-color: rgba(245,158,11,0.5); }

        .mc-top {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.45rem;
        }
        .mc-rank-chip {
            padding: 0.15rem 0.45rem; border-radius: 0.4rem;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            font-size: 0.65rem; font-weight: 900; color: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
        }
        .mc-late-count {
            font-size: 0.82rem; font-weight: 900; color: #f59e0b;
        }

        .mc-photo-frame {
            width: 100%; flex: 1; min-height: 60px;
            border-radius: 0.65rem; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            background: #0f172a;
        }
        .mc-photo-frame img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 20%;
            display: block;
        }

        .mc-details {
            margin-top: 0.45rem; text-align: center;
            background: rgba(2, 6, 23, 0.7);
            padding: 0.35rem 0.25rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .mc-name {
            font-size: 0.78rem; font-weight: 800; color: #ffffff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            line-height: 1.25;
        }
        .mc-class {
            font-size: 0.62rem; font-weight: 800; color: #38bdf8;
            text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px;
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
                <div class="hd-badge">⚡ Papan Kedisiplinan Siswa</div>
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

    {{-- ── FILTER / TITLE ROW ── --}}
    <div class="filter-row">
        <div class="title-block">
            <div class="eyebrow">🏆 Top 10 Peringkat Keterlambatan</div>
            <div class="main-title">Hall of Shame — Siswa Paling Sering Terlambat</div>
        </div>
        <div style="display:flex;align-items:center;gap:0.75rem;">
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

    {{-- ── MAIN AREA ── --}}
    <main>
        @if($students->isNotEmpty())

        {{-- ══ PODIUM TOP 3 (Precision Framed Cards with High-Contrast Typography) ══ --}}
        <div class="podium-row">

            {{-- Rank #2 (Silver - Left) --}}
            @if($students->count() >= 2)
            <div class="podium-card silver">
                <div class="card-banner">
                    <span class="banner-chip">🥈 Rank #2</span>
                    <div class="late-badge">{{ $students[1]->total_terlambat }} <span>Telat</span></div>
                </div>
                <div class="photo-frame">
                    <img src="{{ $students[1]->foto_url }}" alt="{{ $students[1]->nama }}">
                </div>
                <div class="card-details">
                    <div class="card-name" title="{{ $students[1]->nama }}">{{ $students[1]->nama }}</div>
                    <span class="card-class">{{ $students[1]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>
            @endif

            {{-- Rank #1 (Gold - Center, Taller) --}}
            <div class="podium-card gold">
                <div class="card-banner">
                    <span class="banner-chip">👑 Rank #1</span>
                    <div class="late-badge">{{ $students[0]->total_terlambat }} <span>Telat</span></div>
                </div>
                <div class="photo-frame">
                    <img src="{{ $students[0]->foto_url }}" alt="{{ $students[0]->nama }}">
                </div>
                <div class="card-details">
                    <div class="card-name" title="{{ $students[0]->nama }}">{{ $students[0]->nama }}</div>
                    <span class="card-class">{{ $students[0]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>

            {{-- Rank #3 (Bronze - Right) --}}
            @if($students->count() >= 3)
            <div class="podium-card bronze">
                <div class="card-banner">
                    <span class="banner-chip">🥉 Rank #3</span>
                    <div class="late-badge">{{ $students[2]->total_terlambat }} <span>Telat</span></div>
                </div>
                <div class="photo-frame">
                    <img src="{{ $students[2]->foto_url }}" alt="{{ $students[2]->nama }}">
                </div>
                <div class="card-details">
                    <div class="card-name" title="{{ $students[2]->nama }}">{{ $students[2]->nama }}</div>
                    <span class="card-class">{{ $students[2]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>
            @endif

        </div>

        {{-- ══ RANK 4–10 GRID ══ --}}
        @if($students->count() > 3)
        <div class="rank-grid">
            @foreach($students->slice(3) as $index => $s)
            @php $rankNum = $index + 4; @endphp
            <div class="mini-card">
                <div class="mc-top">
                    <span class="mc-rank-chip">#{{ $rankNum }}</span>
                    <span class="mc-late-count">{{ $s->total_terlambat }}×</span>
                </div>
                <div class="mc-photo-frame">
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}">
                </div>
                <div class="mc-details">
                    <div class="mc-name" title="{{ $s->nama }}">{{ $s->nama }}</div>
                    <div class="mc-class">{{ $s->schoolClass->nama_kelas ?? '—' }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

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
