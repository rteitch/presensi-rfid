<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $schoolSettings['kiosk_title'] ?? 'Kiosk Presensi RFID' }} — {{ $schoolSettings['app_name'] }}</title>
    @if($schoolSettings['logo_url'])
        <link rel="icon" type="image/x-icon" href="{{ $schoolSettings['logo_url'] }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            {{ $schoolSettings['kiosk_bg_type'] === 'color' ? 'background-color:' . $schoolSettings['kiosk_bg_color'] . ';' : '' }}
            {{ $schoolSettings['kiosk_bg_type'] === 'gradient' ? 'background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);' : '' }}
            color: #f8fafc;
            font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;
            user-select: none;
        }
        @if($schoolSettings['kiosk_bg_type'] === 'image' && $schoolSettings['kiosk_bg_image_url'])
        body {
            background-image: url('{{ $schoolSettings['kiosk_bg_image_url'] }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            pointer-events: none;
            z-index: 0;
        }
        @endif
        .pulse-ring {
            box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.6);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.6); }
            70% { box-shadow: 0 0 0 20px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between items-center p-6 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Hidden RFID Input (Auto Focus) -->
    <input type="text" id="rfid-input" autofocus autocomplete="off" class="opacity-0 absolute pointer-events-none -top-96">

    @if($schoolSettings['kiosk_bg_type'] === 'image' && $schoolSettings['kiosk_bg_image_url'])
    <div class="bg-overlay"></div>
    @endif

    <!-- Header -->
    <header class="w-full max-w-4xl flex justify-between items-center z-10 py-4">
        <div class="flex items-center gap-3">
            @if($schoolSettings['logo_url'])
                <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="shrink-0 object-contain drop-shadow-md" style="max-height: 48px; max-width: 140px; width: auto; height: auto;">
            @else
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m-8-8h16M4.93 4.93l14.14 14.14M4.93 19.07L19.07 4.93" />
                    </svg>
                </div>
            @endif
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-bold tracking-wider uppercase">{{ $schoolSettings['app_name'] }}</span>
                    <span class="text-slate-400 text-xs">• {{ $schoolSettings['school_name'] }}</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white mt-0.5">{{ $schoolSettings['kiosk_title'] ?? 'PRESENSI RTH NEXUS' }}</h1>
                <p class="text-slate-400 text-sm">{{ $schoolSettings['kiosk_subtitle'] ?? 'Tempelkan Kartu RFID pada Card Reader' }}</p>
            </div>
        </div>
        <div class="text-right flex flex-col items-end gap-1">
            <div id="live-clock" class="text-3xl font-mono font-bold text-slate-100">00:00:00</div>
            <div id="live-date" class="text-xs text-slate-400">Hari, Tanggal</div>
            <a href="{{ route('public.leaderboard') }}" class="mt-1 px-3 py-1 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-bold rounded-lg transition flex items-center gap-1.5 shadow">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Papan Leaderboard</span>
            </a>
        </div>
    </header>

    <!-- Main Card Feedback Display -->
    <main class="w-full max-w-2xl my-auto z-10">
        <div id="display-card" class="bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300 transform scale-100">
            <!-- Icon Status -->
            <div id="icon-container" class="w-24 h-24 mx-auto rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center mb-6 pulse-ring transition-colors duration-300">
                <svg id="icon-rfid" class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m-8-8h16M4.93 4.93l14.14 14.14M4.93 19.07L19.07 4.93" />
                </svg>
            </div>

            <h2 id="main-message" class="text-3xl font-bold text-slate-100 mb-2">Silakan Tap Kartu...</h2>
            <p id="sub-message" class="text-slate-400 text-base">Tempelkan kartu RFID Anda pada alat scanner</p>

            <!-- Student Info (Hidden by default) -->
            <div id="student-info" class="hidden mt-6 pt-6 border-t border-slate-700/60 flex items-center justify-between gap-4 text-left">
                <div class="flex items-center gap-4">
                    <img id="student-photo" src="" alt="Foto Siswa" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-400 shadow-md">
                    <div>
                        <span class="text-xs text-slate-400 uppercase tracking-wider block font-semibold">Nama Siswa</span>
                        <span id="student-name" class="text-xl font-bold text-white block"></span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 uppercase tracking-wider block font-semibold">NIS</span>
                    <span id="student-details" class="text-lg font-mono text-slate-200 block"></span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-4xl text-center text-xs text-slate-400 z-10 py-2 flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ $schoolSettings['app_name'] }} &bull; Klik di layar mana saja untuk mengaktifkan fokus pemindai.</span>
    </footer>

    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
            document.getElementById('live-date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // RFID Input Handling
        const input = document.getElementById('rfid-input');
        const displayCard = document.getElementById('display-card');
        const iconContainer = document.getElementById('icon-container');
        const iconRfid = document.getElementById('icon-rfid');
        const mainMessage = document.getElementById('main-message');
        const subMessage = document.getElementById('sub-message');
        const studentInfo = document.getElementById('student-info');
        const studentPhoto = document.getElementById('student-photo');
        const studentName = document.getElementById('student-name');
        const studentDetails = document.getElementById('student-details');

        // Always keep focus on hidden input
        document.addEventListener('click', () => input.focus());
        setInterval(() => {
            if (document.activeElement !== input) {
                input.focus();
            }
        }, 1000);

        let isProcessing = false;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const uid = input.value.trim();
                input.value = '';

                if (uid !== '' && !isProcessing) {
                    processScan(uid);
                }
            }
        });

        // Advanced Audio Feedback using Web Audio API (No external dependencies, instant playback)
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const audioCtx = new AudioContext();

        function playTone(frequency, type, duration, vol=0.5) {
            try {
                if (audioCtx.state === 'suspended') audioCtx.resume();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.type = type;
                oscillator.frequency.value = frequency;
                gainNode.gain.setValueAtTime(vol, audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + duration);
            } catch(e) {}
        }

        function playSuccessSound() {
            playTone(800, 'sine', 0.1, 0.5);
            setTimeout(() => playTone(1200, 'sine', 0.15, 0.5), 100);
        }

        function playErrorSound() {
            playTone(150, 'sawtooth', 0.3, 0.8);
            setTimeout(() => playTone(120, 'sawtooth', 0.4, 0.8), 200);
        }

        const activeDevices = @json($activeDevices ?? []);
        const urlParams = new URLSearchParams(window.location.search);
        let activeToken = urlParams.get('token') || localStorage.getItem('kiosk_device_token') || '';

        if (!activeToken && activeDevices.length > 0) {
            activeToken = activeDevices[0].token_device;
            localStorage.setItem('kiosk_device_token', activeToken);
        } else if (urlParams.get('token')) {
            localStorage.setItem('kiosk_device_token', urlParams.get('token'));
        }

        // Debounce map to prevent rapid consecutive scans of the same UID
        const recentScans = new Map();

        function processScan(uid) {
            const now = Date.now();
            if (recentScans.has(uid) && (now - recentScans.get(uid)) < 2000) {
                // Prevent duplicate API calls for same UID within 2 seconds (Debouncer)
                isProcessing = false;
                return;
            }
            recentScans.set(uid, now);
            isProcessing = true;

            // UI feedback processing
            displayCard.className = "bg-slate-800/90 backdrop-blur-xl border border-indigo-500/50 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300 transform scale-98";
            mainMessage.innerText = "Memproses...";
            subMessage.innerText = "UID: " + uid;
            studentInfo.classList.add('hidden');

            fetch('/api/rfid/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Device-Token': activeToken
                },
                body: JSON.stringify({ rfid_uid: uid })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    playSuccessSound();

                    displayCard.className = "bg-emerald-950/80 backdrop-blur-xl border border-emerald-500/60 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300 transform scale-105";
                    iconContainer.className = "w-24 h-24 mx-auto rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center mb-6 transition-colors duration-300";
                    iconRfid.outerHTML = `<svg id="icon-rfid" class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;

                    mainMessage.innerText = data.message;
                    subMessage.innerText = "Status Presensi: " + (data.data.status || 'Berhasil');

                    if (data.data && data.data.siswa) {
                        studentName.innerText = data.data.siswa.nama;
                        studentDetails.innerText = data.data.siswa.nis + " (" + (data.data.siswa.kelas || '-') + ")";
                        studentPhoto.src = data.data.siswa.foto_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.data.siswa.nama);
                        studentInfo.classList.remove('hidden');
                    }
                } else {
                    playErrorSound();

                    displayCard.className = "bg-rose-950/80 backdrop-blur-xl border border-rose-500/60 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300 transform scale-105";
                    iconContainer.className = "w-24 h-24 mx-auto rounded-2xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center mb-6 transition-colors duration-300";
                    iconRfid.outerHTML = `<svg id="icon-rfid" class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;

                    mainMessage.innerText = "Gagal Scan!";
                    subMessage.innerText = data.message || "Kartu RFID Anda belum terdaftar di sistem.";
                    studentInfo.classList.add('hidden');
                }

                // Reset after 3 seconds
                setTimeout(resetDisplay, 3200);
            })
            .catch(err => {
                playErrorSound();
                displayCard.className = "bg-rose-950/80 backdrop-blur-xl border border-rose-500/60 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300";
                mainMessage.innerText = "Kesalahan Sistem";
                subMessage.innerText = "Gagal terhubung ke server.";
                studentInfo.classList.add('hidden');
                setTimeout(resetDisplay, 3200);
            });
        }

        function resetDisplay() {
            displayCard.className = "bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 text-center shadow-2xl transition-all duration-300 transform scale-100";
            iconContainer.className = "w-24 h-24 mx-auto rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center mb-6 pulse-ring transition-colors duration-300";
            const iconSvg = document.getElementById('icon-rfid');
            if (iconSvg) {
                iconSvg.outerHTML = `<svg id="icon-rfid" class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m-8-8h16M4.93 4.93l14.14 14.14M4.93 19.07L19.07 4.93" /></svg>`;
            }
            mainMessage.innerText = "Silakan Tap Kartu...";
            subMessage.innerText = "Tempelkan kartu RFID Anda pada alat scanner";
            studentInfo.classList.add('hidden');
            isProcessing = false;
        }
    </script>
</body>
</html>
