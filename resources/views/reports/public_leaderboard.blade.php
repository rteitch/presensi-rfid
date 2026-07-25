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
        .hd-school-name { font-size: 1.05rem; font-weight: 800; letter-spacing: -0.01em; color: #ffffff; }
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
        .clock-time { font-size: 1.55rem; font-weight: 900; letter-spacing: 0.04em; color: #f59e0b; line-height: 1; font-family: monospace; }
        .clock-date { font-size: 0.65rem; color: #94a3b8; font-weight: 600; margin-top: 2px; }

        /* ── Sub Header / Filter Row ── */
        .filter-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.55rem 1.75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(15,23,42,0.4);
        }
        .title-block .eyebrow { font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b; font-weight: 900; }
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
            gap: 0.75rem; padding: 0.75rem 1.75rem 1rem;
            min-height: 0; overflow: hidden;
        }

        /* ── 5x2 GRID LAYOUT (Left to Right: #1 to #10) ── */
        .cards-grid-5x2 {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 0.85rem;
            flex: 1; min-height: 0;
        }

        .student-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border-radius: 1rem;
            padding: 0.55rem;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            border: 1.5px solid rgba(255,255,255,0.1);
        }
        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* Top 3 Special Card Highlight Styling */
        .student-card.rank-1 {
            border: 2.5px solid #f59e0b;
            box-shadow: 0 0 30px rgba(245,158,11,0.25), 0 12px 32px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(245,158,11,0.15) 0%, rgba(15,23,42,0.95) 100%);
        }
        .student-card.rank-2 {
            border: 2px solid #94a3b8;
            box-shadow: 0 0 25px rgba(148,163,184,0.18), 0 12px 28px rgba(0,0,0,0.4);
            background: linear-gradient(180deg, rgba(148,163,184,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }
        .student-card.rank-3 {
            border: 2px solid #b45309;
            box-shadow: 0 0 25px rgba(180,83,9,0.18), 0 12px 28px rgba(0,0,0,0.4);
            background: linear-gradient(180deg, rgba(180,83,9,0.12) 0%, rgba(15,23,42,0.95) 100%);
        }

        /* Top Header inside Card (Rank Badge + Terlambat Count) */
        .card-top-bar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.4rem; z-index: 2;
        }

        .rank-pill {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.65rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1;
        }
        .rank-1 .rank-pill { background: #f59e0b; color: #1c0a00; border: none; box-shadow: 0 2px 10px rgba(245,158,11,0.4); }
        .rank-2 .rank-pill { background: #94a3b8; color: #0f172a; border: none; box-shadow: 0 2px 10px rgba(148,163,184,0.3); }
        .rank-3 .rank-pill { background: #b45309; color: #ffffff; border: none; box-shadow: 0 2px 10px rgba(180,83,9,0.3); }

        .late-count-badge {
            font-size: 0.9rem; font-weight: 900; color: #f59e0b;
            background: rgba(2,6,23,0.8); padding: 0.2rem 0.55rem; border-radius: 0.45rem;
            border: 1px solid rgba(245,158,11,0.3);
        }
        .late-count-badge span { font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; }

        /* Large Full Portrait Photo Box */
        .photo-box {
            width: 100%; flex: 1; min-height: 100px;
            border-radius: 0.75rem; overflow: hidden;
            border: 1.5px solid rgba(255,255,255,0.15);
            background: #0f172a; position: relative;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.4);
        }
        .rank-1 .photo-box { border-color: rgba(245,158,11,0.5); }
        .photo-box img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 20%;
            display: block;
        }

        /* Bottom Info Box (Student Name & Class) */
        .info-box {
            margin-top: 0.45rem; text-align: center;
            background: rgba(2, 6, 23, 0.85);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.6rem;
            padding: 0.4rem 0.3rem;
        }
        .student-name {
            font-size: 0.92rem; font-weight: 900; color: #ffffff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            line-height: 1.25; letter-spacing: -0.01em;
            text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        }
        .rank-1 .student-name { font-size: 1rem; }

        .student-class {
            display: inline-block; margin-top: 0.2rem;
            font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            padding: 0.15rem 0.55rem; border-radius: 0.35rem;
            color: #38bdf8; background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.3);
        }
        .rank-1 .student-class { background: rgba(245,158,11,0.25); color: #fde68a; border: 1px solid rgba(245,158,11,0.4); }
        .rank-2 .student-class { background: rgba(148,163,184,0.25); color: #f1f5f9; border: 1px solid rgba(148,163,184,0.35); }
        .rank-3 .student-class { background: rgba(180,83,9,0.25); color: #fed7aa; border: 1px solid rgba(180,83,9,0.35); }

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

    {{-- ── MAIN AREA: 5x2 SEQUENTIAL GRID (#1 to #10 Left to Right) ── --}}
    <main>
        @if($students->isNotEmpty())

        <div class="cards-grid-5x2">
            @foreach($students as $index => $s)
            @php
                $rankNum = $index + 1;
                $rankClass = $rankNum === 1 ? 'rank-1' : ($rankNum === 2 ? 'rank-2' : ($rankNum === 3 ? 'rank-3' : ''));
                $rankLabel = $rankNum === 1 ? '👑 #1' : ($rankNum === 2 ? '🥈 #2' : ($rankNum === 3 ? '🥉 #3' : '#'.$rankNum));
            @endphp
            <div class="student-card {{ $rankClass }}">
                {{-- Card Top Bar --}}
                <div class="card-top-bar">
                    <span class="rank-pill">{{ $rankLabel }}</span>
                    <div class="late-count-badge">{{ $s->total_terlambat }} <span>Telat</span></div>
                </div>

                {{-- Full Portrait Photo --}}
                <div class="photo-box">
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}">
                </div>

                {{-- Bottom Info Box --}}
                <div class="info-box">
                    <div class="student-name" title="{{ $s->nama }}">{{ $s->nama }}</div>
                    <span class="student-class">{{ $s->schoolClass->nama_kelas ?? '—' }}</span>
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
