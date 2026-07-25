<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $schoolSettings['app_name'] ?? 'PRESENSI RTH NEXUS' }} — {{ $schoolSettings['school_name'] ?? 'Sistem Presensi RFID' }}</title>

    @if(isset($schoolSettings['logo_url']) && $schoolSettings['logo_url'])
        <link rel="icon" type="image/png" href="{{ $schoolSettings['logo_url'] }}">
    @endif

    <!-- Fonts & Assets (bundled locally via Vite - Offline Ready) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #0f172a 100%);
            min-height: 100vh;
            color: #f8fafc;
        }
    </style>
</head>
<body class="antialiased flex flex-col justify-between min-h-screen relative overflow-x-hidden">

    <!-- Background Accents -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between z-10">
        <div class="flex items-center gap-3">
            @if(isset($schoolSettings['logo_url']) && $schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="h-10 w-auto object-contain drop-shadow">
            @else
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            @endif
            <div>
                <div class="text-white font-extrabold text-sm tracking-wider uppercase text-indigo-300">{{ $schoolSettings['app_name'] ?? 'PRESENSI RTH NEXUS' }}</div>
                <div class="text-slate-400 text-xs font-medium">{{ $schoolSettings['school_name'] ?? 'Sekolah' }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('kiosk.scan') }}" target="_blank"
               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold transition backdrop-blur-sm">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Mode Kiosk Scanner
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg flex items-center gap-2">
                    <span>Masuk Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg flex items-center gap-2">
                    <span>Login Petugas / Guru</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="w-full max-w-5xl mx-auto px-6 py-12 my-auto z-10 text-center space-y-8">
        
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold tracking-wide uppercase shadow-inner">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Sistem Presensi Sekolah Modern v2.0</span>
        </div>

        <div class="space-y-4 max-w-3xl mx-auto">
            <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white leading-tight">
                Presensi RFID Otomatis & Monitoring Real-Time
            </h1>
            <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                Kelola kedisiplinan dan data kehadiran siswa <strong class="text-white">{{ $schoolSettings['school_name'] ?? '' }}</strong> dengan pemindaian kartu RFID instant, laporan otomatis, dan notifikasi WhatsApp ke orang tua.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="{{ route('kiosk.scan') }}" target="_blank"
               class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition shadow-xl hover:shadow-indigo-500/25 flex items-center gap-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Buka Layar Kiosk Scanner</span>
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-8 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm transition backdrop-blur-sm flex items-center gap-2">
                    <span>Ke Dashboard Admin</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-8 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm transition backdrop-blur-sm flex items-center gap-2">
                    <span>Login Administrator</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @endauth
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-8 text-left">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-bold text-white text-base">Tap RFID Instant</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Pengenalan kartu RFID super cepat dengan konfirmasi audio & visual status kehadiran.</p>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="font-bold text-white text-base">Notifikasi WA Ortu</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Kirim rekap dan peringatan keterlambatan ke WhatsApp wali murid dalam 1 klik.</p>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-2">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-amber-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-bold text-white text-base">Leaderboard & Rekap</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Rangking tingkat keterlambatan dan rekap data presensi bulanan lengkap siap PDF.</p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full text-center text-xs text-slate-400 z-10 py-6 border-t border-white/10">
        {{ $schoolSettings['footer_text'] ?? 'PRESENSI RTH NEXUS' }}
    </footer>

</body>
</html>
