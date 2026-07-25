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
                radial-gradient(ellipse 130% 60% at 50% -10%, rgba(99,102,241,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 90%,  rgba(245,158,11,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 15% 90%,  rgba(139,92,246,0.18) 0%, transparent 55%),
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
            border-bottom: 1px solid rgba(255,255,255,0.08);
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

        /* ── STUDENT CARD (Premium Trading Card Style) ── */
        .student-card {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(16px);
            border-radius: 1.1rem;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
            transition: transform 0.3s cubic-bezier(.34,1.56,.64,1), box-shadow 0.3s ease;
            border: 1.5px solid rgba(255,255,255,0.1);
        }
        .student-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 35px rgba(0,0,0,0.6);
        }

        /* 🥇 Rank #1 Gold Glow (The Ultimate Icon Card) */
        .student-card.rank-1 {
            border: 2.5px solid #f59e0b;
            box-shadow: 0 0 50px rgba(245,158,11,0.4), 0 16px 40px rgba(0,0,0,0.6);
            background: linear-gradient(180deg, rgba(245,158,11,0.2) 0%, rgba(15,23,42,0.95) 100%);
        }
        /* 🥈 Rank #2 Silver Glow */
        .student-card.rank-2 {
            border: 2px solid #94a3b8;
            box-shadow: 0 0 30px rgba(148,163,184,0.22), 0 12px 32px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(148,163,184,0.15) 0%, rgba(15,23,42,0.95) 100%);
        }
        /* 🥉 Rank #3 Bronze Glow */
        .student-card.rank-3 {
            border: 2px solid #b45309;
            box-shadow: 0 0 30px rgba(180,83,9,0.22), 0 12px 32px rgba(0,0,0,0.5);
            background: linear-gradient(180deg, rgba(180,83,9,0.15) 0%, rgba(15,23,42,0.95) 100%);
        }
        /* Rank 4-10 Neon Border */
        .student-card.rank-standard {
            border: 1.5px solid rgba(99, 102, 241, 0.2);
        }
        .student-card.rank-standard:hover {
            border-color: rgba(99, 102, 241, 0.5);
        }

        /* 👑 Top 1 Crown Overlay */
        .top1-crown {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            z-index: 20; font-size: 1.6rem;
            filter: drop-shadow(0 4px 12px rgba(245,158,11,0.8));
        }

        /* ── PHOTO CONTAINER (Dominant ~65% Height) ── */
        .photo-container {
            width: 100%; flex: 1; min-height: 110px;
            position: relative; overflow: hidden;
            background: #090d16;
        }
        .photo-img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center 20%;
            display: block;
            transition: transform 0.5s ease;
        }
        .student-card:hover .photo-img { transform: scale(1.05); }

        /* Photo Bottom Gradient Overlay for Seamless Integration */
        .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.98) 0%, rgba(15,23,42,0.4) 40%, transparent 80%);
        }

        /* Rank Watermark Badge Top Right */
        .rank-watermark {
            position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;
            padding: 0.2rem 0.6rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.06em;
            backdrop-filter: blur(8px);
            background: rgba(2,6,23,0.7); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1;
        }
        .rank-1 .rank-watermark { background: #f59e0b; color: #1c0a00; border: none; font-size: 0.8rem; box-shadow: 0 2px 10px rgba(245,158,11,0.4); }
        .rank-2 .rank-watermark { background: #94a3b8; color: #0f172a; border: none; font-size: 0.78rem; box-shadow: 0 2px 10px rgba(148,163,184,0.3); }
        .rank-3 .rank-watermark { background: #b45309; color: #ffffff; border: none; font-size: 0.78rem; box-shadow: 0 2px 10px rgba(180,83,9,0.3); }

        /* ── INFO PANEL (Primary Focus: Name → Late → Class) ── */
        .info-panel {
            position: relative; z-index: 10;
            padding: 0.55rem 0.65rem 0.65rem;
            text-align: center; display: flex; flex-direction: column; gap: 0.35rem;
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* STUDENT NAME — HUGE, BOLD, HIGH CONTRAST (Readability in 2 Seconds) */
        .student-name {
            font-size: clamp(0.95rem, 1.25vw, 1.25rem);
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-transform: uppercase;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            text-shadow: 0 2px 8px rgba(0,0,0,0.9);
        }
        .rank-1 .student-name {
            font-size: clamp(1.1rem, 1.4vw, 1.4rem);
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(245,158,11,0.5);
        }

        /* ⚠️ LATE COUNT BADGE — HUGE & UNMISSABLE */
        .late-count-box {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem;
            padding: 0.3rem 0.7rem; border-radius: 0.55rem;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.25) 0%, rgba(245, 158, 11, 0.2) 100%);
            border: 1.5px solid rgba(239, 68, 68, 0.45);
            color: #ef4444; font-size: 0.88rem; font-weight: 900;
            align-self: center;
        }
        .late-count-box .late-num { font-size: 1.05rem; font-weight: 900; color: #f59e0b; }
        .rank-1 .late-count-box {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.35) 0%, rgba(220, 38, 38, 0.3) 100%);
            border-color: rgba(245, 158, 11, 0.6);
        }

        /* CLASS CHIP — COLOR CODED BY GRADE LEVEL (X=Cyan, XI=Emerald, XII=Purple) */
        .class-chip {
            display: inline-flex; align-self: center;
            font-size: 0.68rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em;
            padding: 0.2rem 0.65rem; border-radius: 0.45rem;
        }
        /* Grade 10 / X / 7 */
        .class-grade-10 {
            background: rgba(6, 182, 212, 0.18); color: #67e8f9; border: 1.5px solid rgba(6, 182, 212, 0.35);
        }
        /* Grade 11 / XI / 8 */
        .class-grade-11 {
            background: rgba(16, 185, 129, 0.18); color: #6ee7b7; border: 1.5px solid rgba(16, 185, 129, 0.35);
        }
        /* Grade 12 / XII / 9 */
        .class-grade-12 {
            background: rgba(168, 85, 247, 0.18); color: #d8b4fe; border: 1.5px solid rgba(168, 85, 247, 0.35);
        }

        /* Extra Useful Info Footer: Terakhir Terlambat */
        .last-late-text {
            font-size: 0.58rem; color: #64748b; font-weight: 700; margin-top: 1px;
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

    {{-- ── MAIN AREA: 5x2 SEQUENTIAL GRID (Left to Right #1 to #10) ── --}}
    <main>
        @if($students->isNotEmpty())

        <div class="cards-grid-5x2">
            @foreach($students as $index => $s)
            @php
                $rankNum = $index + 1;
                $rankClass = $rankNum === 1 ? 'rank-1' : ($rankNum === 2 ? 'rank-2' : ($rankNum === 3 ? 'rank-3' : 'rank-standard'));
                $rankLabel = $rankNum === 1 ? '👑 #01' : ($rankNum === 2 ? '🥈 #02' : ($rankNum === 3 ? '🥉 #03' : sprintf('#%02d', $rankNum)));

                // Grade-based color class
                $cName = strtoupper($s->schoolClass->nama_kelas ?? '');
                if (str_contains($cName, 'XII') || str_contains($cName, '12') || str_contains($cName, 'IX') || str_contains($cName, '9')) {
                    $gradeColorClass = 'class-grade-12';
                } elseif (str_contains($cName, 'XI') || str_contains($cName, '11') || str_contains($cName, 'VIII') || str_contains($cName, '8')) {
                    $gradeColorClass = 'class-grade-11';
                } else {
                    $gradeColorClass = 'class-grade-10';
                }

                $lastLate = $s->attendances ? $s->attendances->first() : null;
                $lastLateDate = $lastLate ? \Carbon\Carbon::parse($lastLate->tanggal)->translatedFormat('d M') : null;
            @endphp
            <div class="student-card {{ $rankClass }}">
                {{-- Top 1 Crown Badge Overlay --}}
                @if($rankNum === 1)
                    <div class="top1-crown">👑</div>
                @endif

                {{-- Dominant Photo Container (~65% height) --}}
                <div class="photo-container">
                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="photo-img">
                    <div class="photo-overlay"></div>
                    <div class="rank-watermark">{{ $rankLabel }}</div>
                </div>

                {{-- High-Contrast Info Panel: 👤 Foto → 📝 Nama → ⏰ Terlambat → 🎓 Kelas --}}
                <div class="info-panel">
                    {{-- 1. HUGE STUDENT NAME --}}
                    <div class="student-name" title="{{ $s->nama }}">{{ strtoupper($s->nama) }}</div>

                    {{-- 2. UNMISSABLE LATE COUNT BADGE --}}
                    <div class="late-count-box">
                        <span>⚠️</span>
                        <span class="late-num">{{ $s->total_terlambat }}×</span>
                        <span style="font-size:0.6rem;font-weight:800;color:#f8fafc;letter-spacing:0.04em;">TERLAMBAT</span>
                    </div>

                    {{-- 3. GRADE COLOR-CODED CLASS CHIP --}}
                    <div class="class-chip {{ $gradeColorClass }}">
                        {{ $s->schoolClass->nama_kelas ?? '—' }}
                    </div>

                    {{-- 4. TERAKHIR TERLAMBAT --}}
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
