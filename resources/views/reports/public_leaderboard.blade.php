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
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #020617;
            color: #f8fafc;
            overflow: hidden; /* Full-screen: no scroll */
        }

        /* ── Animated Background ── */
        .bg-cosmos {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 140% 70% at 50% -10%, rgba(109,40,217,0.35) 0%, transparent 55%),
                radial-gradient(ellipse 70% 50% at 95% 90%,  rgba(245,158,11,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 5%  90%,  rgba(99,102,241,0.15) 0%, transparent 55%),
                #020617;
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 72px 72px;
        }
        .bg-vignette {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(ellipse 100% 100% at 50% 50%, transparent 40%, rgba(2,6,23,0.55) 100%);
        }

        /* ── Page layout: 100vh, no scroll ── */
        .page { position: relative; z-index: 1; height: 100vh; display: flex; flex-direction: column; }

        /* ── Header ── */
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.6rem 1.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: rgba(2,6,23,0.65);
            backdrop-filter: blur(24px);
            flex-shrink: 0;
        }
        .hd-left  { display: flex; align-items: center; gap: 0.85rem; }
        .hd-logo  { height: 2.6rem; width: auto; object-fit: contain; }
        .hd-logo-placeholder {
            width: 42px; height: 42px; border-radius: 0.75rem;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .hd-school-name { font-size: 0.95rem; font-weight: 800; letter-spacing: -0.01em; }
        .hd-badge {
            font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.12em;
            color: #f59e0b; font-weight: 700;
        }

        .live-pill {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.28rem 0.75rem; border-radius: 9999px;
            background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);
            color: #34d399; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
        }
        .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; animation: blink 1.4s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .clock-wrap { text-align: right; }
        .clock-time { font-size: 1.4rem; font-weight: 900; letter-spacing: 0.04em; color: #f59e0b; line-height: 1; }
        .clock-date { font-size: 0.6rem; color: #475569; margin-top: 2px; }

        /* ── Filter bar ── */
        .filter-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.5rem 1.75rem; flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .title-block .eyebrow { font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b; font-weight: 800; }
        .title-block .main-title {
            font-size: clamp(1.1rem, 2vw, 1.5rem); font-weight: 900; letter-spacing: -0.03em;
            background: linear-gradient(90deg, #fff 30%, #fde68a 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            line-height: 1.1;
        }
        .period-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.28rem 0.75rem; border-radius: 9999px;
            background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2);
            color: #f59e0b; font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em;
        }
        .filter-controls { display: flex; align-items: center; gap: 0.5rem; }
        .fi { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; font-size: 0.7rem; border-radius: 0.5rem; padding: 0.32rem 0.7rem; font-family: inherit; outline: none; }
        .fi:focus { border-color: #f59e0b; }
        .fi option { background: #1e293b; }
        .fb { background: #f59e0b; color: #1c0a00; font-size: 0.7rem; font-weight: 800; padding: 0.32rem 0.85rem; border-radius: 0.5rem; border: none; cursor: pointer; font-family: inherit; }
        .fb:hover { background: #fbbf24; }
        .rb { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; font-size: 0.7rem; font-weight: 700; padding: 0.32rem 0.7rem; border-radius: 0.5rem; text-decoration: none; font-family: inherit; }

        /* ── Main cards area ── */
        main {
            flex: 1; display: flex; flex-direction: column;
            gap: 0.75rem; padding: 0.75rem 1.75rem 0.75rem;
            overflow: hidden; min-height: 0;
        }

        /* ── TOP 3 PODIUM ── */
        .podium-row {
            display: grid;
            grid-template-columns: 1fr 1.22fr 1fr;
            gap: 0.75rem;
            flex: 1.6; min-height: 0;
        }

        /* ── SPORT CARD (full photo) ── */
        .sport-card {
            position: relative; border-radius: 1.1rem; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-end;
            cursor: default;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s;
        }
        .sport-card:hover { transform: translateY(-5px) scale(1.015); }

        /* photo fills entire card */
        .sport-card .sc-photo {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover; object-position: top center;
            transition: transform 0.6s ease;
        }
        .sport-card:hover .sc-photo { transform: scale(1.04); }

        /* gradient overlay bottom */
        .sport-card .sc-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top,
                rgba(2,6,23,0.96) 0%,
                rgba(2,6,23,0.7)  35%,
                rgba(2,6,23,0.15) 65%,
                transparent       100%);
        }

        /* gold glow ring */
        .sport-card.gold { box-shadow: 0 0 0 2px #f59e0b, 0 0 40px rgba(245,158,11,0.35), 0 16px 48px rgba(0,0,0,0.6); }
        .sport-card.silver { box-shadow: 0 0 0 1.5px #94a3b8, 0 0 28px rgba(148,163,184,0.2), 0 16px 40px rgba(0,0,0,0.5); }
        .sport-card.bronze { box-shadow: 0 0 0 1.5px #b45309, 0 0 28px rgba(180,83,9,0.2), 0 16px 40px rgba(0,0,0,0.5); }

        /* Top rank badge */
        .sc-rank-badge {
            position: absolute; top: 0.75rem; left: 0.75rem; z-index: 10;
            display: flex; align-items: center; gap: 0.3rem;
            padding: 0.3rem 0.65rem; border-radius: 9999px;
            font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em;
            backdrop-filter: blur(8px);
        }
        .gold   .sc-rank-badge { background: rgba(245,158,11,0.9); color: #1c0a00; }
        .silver .sc-rank-badge { background: rgba(148,163,184,0.85); color: #0f172a; }
        .bronze .sc-rank-badge { background: rgba(180,83,9,0.9); color: #fff; }

        /* Count badge top right */
        .sc-count-badge {
            position: absolute; top: 0.75rem; right: 0.75rem; z-index: 10;
            display: flex; flex-direction: column; align-items: center;
            padding: 0.4rem 0.6rem; border-radius: 0.65rem;
            backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.12);
            background: rgba(0,0,0,0.45);
        }
        .sc-count-num { font-size: 1.3rem; font-weight: 900; line-height: 1; }
        .sc-count-label { font-size: 0.48rem; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-top: 1px; }
        .gold   .sc-count-num { color: #f59e0b; font-size: 1.6rem; }
        .silver .sc-count-num { color: #e2e8f0; }
        .bronze .sc-count-num { color: #d97706; }

        /* Bottom info */
        .sc-info {
            position: relative; z-index: 10;
            padding: 0.85rem 0.9rem 0.9rem;
        }
        .sc-name { font-size: 0.95rem; font-weight: 800; letter-spacing: -0.02em; color: #fff; line-height: 1.2; }
        .gold .sc-name { font-size: 1.05rem; }
        .sc-class {
            display: inline-block; margin-top: 0.3rem;
            font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;
            padding: 0.18rem 0.55rem; border-radius: 0.35rem;
        }
        .gold   .sc-class { background: rgba(245,158,11,0.2); color: #fcd34d; border: 1px solid rgba(245,158,11,0.3); }
        .silver .sc-class { background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.25); }
        .bronze .sc-class { background: rgba(180,83,9,0.15); color: #d97706; border: 1px solid rgba(180,83,9,0.25); }

        /* ── RANK 4-10 ROW ── */
        .rank-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.6rem;
            flex: 1; min-height: 0;
        }

        /* Small sport card */
        .mini-card {
            position: relative; border-radius: 0.85rem; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-end;
            transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.25s;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.07);
        }
        .mini-card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 12px 32px rgba(0,0,0,0.5); }

        .mini-card .mc-photo {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover; object-position: top center;
            transition: transform 0.5s ease;
        }
        .mini-card:hover .mc-photo { transform: scale(1.06); }

        .mini-card .mc-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(2,6,23,0.95) 0%, rgba(2,6,23,0.6) 40%, rgba(2,6,23,0.1) 70%, transparent 100%);
        }

        .mc-rank {
            position: absolute; top: 0.5rem; left: 0.5rem; z-index: 10;
            width: 22px; height: 22px; border-radius: 50%;
            background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.15);
            font-size: 0.58rem; font-weight: 900; color: #94a3b8;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .mc-count {
            position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;
            background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.4rem; padding: 0.18rem 0.4rem; backdrop-filter: blur(4px);
            font-size: 0.8rem; font-weight: 900; color: #f59e0b; line-height: 1;
        }
        .mc-count-label { font-size: 0.4rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }

        .mc-info { position: relative; z-index: 10; padding: 0.55rem 0.6rem 0.6rem; }
        .mc-name { font-size: 0.7rem; font-weight: 800; color: #f1f5f9; line-height: 1.2; letter-spacing: -0.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mc-class { font-size: 0.52rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 2px; }

        /* ── Footer ── */
        footer {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.4rem 1.75rem;
            border-top: 1px solid rgba(255,255,255,0.04);
            flex-shrink: 0; font-size: 0.58rem; color: #1e293b;
        }
        footer a { color: #334155; text-decoration: none; }
        footer a:hover { color: #475569; }

        /* ── Empty state ── */
        .empty-state {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 0.75rem;
            text-align: center;
        }

        /* Scrollbar (hidden) */
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>
<div class="bg-cosmos"></div>
<div class="bg-grid"></div>
<div class="bg-vignette"></div>

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

    {{-- ── MAIN CONTENT ── --}}
    <main>

        @if($students->isNotEmpty())

        {{-- ══ TOP 3 PODIUM — Full Photo Sport Cards ══ --}}
        <div class="podium-row">

            {{-- #2 Silver --}}
            @if($students->count() >= 2)
            <div class="sport-card silver">
                <img src="{{ $students[1]->foto_url }}" alt="{{ $students[1]->nama }}" class="sc-photo">
                <div class="sc-overlay"></div>
                <div class="sc-rank-badge">🥈 Rank #2</div>
                <div class="sc-count-badge">
                    <span class="sc-count-num">{{ $students[1]->total_terlambat }}</span>
                    <span class="sc-count-label">× terlambat</span>
                </div>
                <div class="sc-info">
                    <div class="sc-name">{{ $students[1]->nama }}</div>
                    <span class="sc-class">{{ $students[1]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>
            @else <div></div>
            @endif

            {{-- #1 Gold (center) --}}
            <div class="sport-card gold">
                <img src="{{ $students[0]->foto_url }}" alt="{{ $students[0]->nama }}" class="sc-photo">
                <div class="sc-overlay"></div>
                <div class="sc-rank-badge">👑 Rank #1</div>
                <div class="sc-count-badge">
                    <span class="sc-count-num">{{ $students[0]->total_terlambat }}</span>
                    <span class="sc-count-label">× terlambat</span>
                </div>
                <div class="sc-info">
                    <div class="sc-name">{{ $students[0]->nama }}</div>
                    <span class="sc-class">{{ $students[0]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>

            {{-- #3 Bronze --}}
            @if($students->count() >= 3)
            <div class="sport-card bronze">
                <img src="{{ $students[2]->foto_url }}" alt="{{ $students[2]->nama }}" class="sc-photo">
                <div class="sc-overlay"></div>
                <div class="sc-rank-badge">🥉 Rank #3</div>
                <div class="sc-count-badge">
                    <span class="sc-count-num">{{ $students[2]->total_terlambat }}</span>
                    <span class="sc-count-label">× terlambat</span>
                </div>
                <div class="sc-info">
                    <div class="sc-name">{{ $students[2]->nama }}</div>
                    <span class="sc-class">{{ $students[2]->schoolClass->nama_kelas ?? '—' }}</span>
                </div>
            </div>
            @else <div></div>
            @endif

        </div>

        {{-- ══ RANK 4–10 Mini Cards Row ══ --}}
        @if($students->count() > 3)
        <div class="rank-row">
            @foreach($students->slice(3) as $index => $s)
            @php $rankNum = $index + 4; @endphp
            <div class="mini-card">
                <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="mc-photo">
                <div class="mc-overlay"></div>
                <div class="mc-rank">#{{ $rankNum }}</div>
                <div class="mc-count">
                    {{ $s->total_terlambat }}×
                    <div class="mc-count-label">terlambat</div>
                </div>
                <div class="mc-info">
                    <div class="mc-name">{{ $s->nama }}</div>
                    <div class="mc-class">{{ $s->schoolClass->nama_kelas ?? '—' }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="empty-state">
            <div style="font-size:5rem;">🎉</div>
            <div style="font-size:1.5rem;font-weight:900;color:#fff;">Tidak Ada Keterlambatan!</div>
            <div style="font-size:0.85rem;color:#475569;">Semua siswa hadir tepat waktu pada periode ini.</div>
        </div>
        @endif

    </main>

    {{-- ── FOOTER ── --}}
    <footer>
        <span>© {{ date('Y') }} {{ $schoolSettings['school_name'] }}</span>
        <span style="color:#1e3a5f;font-size:0.55rem;">Auto-refresh setiap 30 detik</span>
        <a href="{{ route('kiosk.scan') }}">↗ Buka Kiosk Scan · Powered by {{ $schoolSettings['app_name'] }}</a>
    </footer>

</div>

<script>
    // Live clock
    (function tick() {
        const now = new Date();
        const c = document.getElementById('live-clock');
        const d = document.getElementById('live-date');
        if (c) c.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        setTimeout(tick, 1000);
    })();

    // Auto-refresh 30s for hall-display
    setTimeout(() => location.reload(), 30000);
</script>
</body>
</html>
