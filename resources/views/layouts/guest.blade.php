<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $schoolSettings['app_name'] ?? 'PRESENSI RTH NEXUS' }} — {{ $schoolSettings['school_name'] ?? 'Masuk' }}</title>

    @if(isset($schoolSettings['logo_url']) && $schoolSettings['logo_url'])
        <link rel="icon" type="image/png" href="{{ $schoolSettings['logo_url'] }}">
    @endif

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        body {
            background: {{ isset($schoolSettings['kiosk_bg_color']) && $schoolSettings['kiosk_bg_color'] ? $schoolSettings['kiosk_bg_color'] : 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%)' }};
            min-height: 100vh;
            color: #f8fafc;
        }
        @if(isset($schoolSettings['kiosk_bg_type']) && $schoolSettings['kiosk_bg_type'] === 'image' && isset($schoolSettings['kiosk_bg_image_url']) && $schoolSettings['kiosk_bg_image_url'])
        body {
            background-image: url('{{ $schoolSettings['kiosk_bg_image_url'] }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            pointer-events: none;
            z-index: 0;
        }
        @endif
    </style>
</head>
<body class="antialiased flex flex-col justify-between items-center p-6 relative min-h-screen">

    @if(isset($schoolSettings['kiosk_bg_type']) && $schoolSettings['kiosk_bg_type'] === 'image' && isset($schoolSettings['kiosk_bg_image_url']) && $schoolSettings['kiosk_bg_image_url'])
        <div class="bg-overlay"></div>
    @endif

    <!-- Background Accents -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md my-auto z-10 space-y-6">

        <!-- Logo & Brand Header -->
        <div class="text-center space-y-2">
            @if(isset($schoolSettings['logo_url']) && $schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="h-16 w-auto object-contain mx-auto drop-shadow-md">
            @else
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center mx-auto shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            @endif

            <div>
                <h1 class="text-2xl font-black tracking-wider text-white uppercase">{{ $schoolSettings['app_name'] ?? 'PRESENSI RTH NEXUS' }}</h1>
                <p class="text-xs text-slate-300 font-medium mt-0.5">{{ $schoolSettings['school_name'] ?? 'Sistem Presensi RFID Sekolah' }}</p>
            </div>
        </div>

        <!-- Auth Form Card -->
        <div class="bg-white/95 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl text-slate-900">
            {{ $slot }}
        </div>

    </div>

    <!-- Footer -->
    <footer class="w-full text-center text-xs text-slate-400 z-10 py-4">
        {{ $schoolSettings['footer_text'] ?? 'PRESENSI RTH NEXUS' }}
    </footer>

</body>
</html>
