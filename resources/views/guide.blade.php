<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 border border-indigo-100 rounded-xl shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Panduan Aplikasi & Petunjuk Operasional</h1>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Panduan resmi alur kerja RTH NEXUS untuk Administrator (IT/BK) dan Guru Wali Kelas.</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ activeTab: 'admin' }">

        <!-- Banner Selamat Datang -->
        <div class="rounded-xl p-6 shadow-sm border border-indigo-900/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4"
             style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); color: #ffffff;">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
                     style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff;">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Petunjuk Penggunaan Terintegrasi Role System</span>
                </div>
                <h2 class="text-xl font-extrabold tracking-tight" style="color: #ffffff !important;">
                    Sistem Presensi Kartu RFID Mandiri & Realtime
                </h2>
                <p class="text-xs leading-relaxed" style="color: rgba(255, 255, 255, 0.88) !important;">
                    Pilih petunjuk sesuai dengan peran Anda di sekolah di bawah ini untuk melihat alur kerja operasional yang relevan.
                </p>
            </div>
            <a href="{{ route('kiosk.scan') }}" target="_blank"
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition shadow-md flex items-center gap-2 shrink-0"
               style="background: #ffffff; color: #3730a3 !important;">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Buka Layar Kiosk</span>
            </a>
        </div>

        <!-- Role Selector Tab Switcher -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
            <button @click="activeTab = 'admin'"
                    :class="activeTab === 'admin' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>👑 Panduan Administrator (Admin IT / Guru BK)</span>
            </button>
            <button @click="activeTab = 'guru'"
                    :class="activeTab === 'guru' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>👨‍🏫 Panduan Guru / Wali Kelas</span>
            </button>
        </div>

        <!-- Content Tab 1: Administrator (IT / BK) -->
        <div x-show="activeTab === 'admin'" x-transition class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                <!-- Admin Step 1 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #4f46e5;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #4f46e5; color: #ffffff !important;">1</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 1</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Konfigurasi & Pengguna</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Atur Jam Masuk/Pulang, nama & logo sekolah, serta buat akun pengguna (Admin / Guru Wali Kelas) di menu Manajemen Pengguna.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex flex-col gap-1.5">
                        <a href="{{ route('settings.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">1. Aturan Jam Masuk &rarr;</a>
                        <a href="{{ route('settings.school') }}" class="text-xs font-semibold text-indigo-600 hover:underline">2. Identitas Sekolah &rarr;</a>
                        <a href="{{ route('users.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">3. Manajemen Pengguna &rarr;</a>
                    </div>
                </div>

                <!-- Admin Step 2 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #2563eb;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #2563eb; color: #ffffff !important;">2</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 2</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Data Master & Import Excel</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Kelola data Kelas, Wali Kelas, Guru/Dosen, serta Siswa. Gunakan fitur Import Excel untuk memasukkan ratusan siswa sekaligus.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex flex-col gap-1.5">
                        <a href="{{ route('classes.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">1. Data Kelas & Wali Kelas &rarr;</a>
                        <a href="{{ route('teachers.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">2. Data Guru / Dosen &rarr;</a>
                        <a href="{{ route('students.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">3. Data Siswa & RFID &rarr;</a>
                    </div>
                </div>

                <!-- Admin Step 3 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #d97706;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #d97706; color: #ffffff !important;">3</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 3</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Registrasi Device RFID</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Daftarkan perangkat pemindai RFID baru untuk mendapatkan token autentikasi API (<span class="font-mono font-semibold text-slate-700 text-[11px]">X-Device-Token</span>).
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('devices.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Manajemen Device RFID &rarr;</a>
                    </div>
                </div>

                <!-- Admin Step 4 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #059669;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #059669; color: #ffffff !important;">4</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 4</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Jalankan Layar Kiosk</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Buka halaman Kiosk Scanner di komputer gerbang/lobby. Siswa melakukan tap kartu RFID dengan konfirmasi suara audio chime.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('kiosk.scan') }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline">Buka Mode Kiosk &rarr;</a>
                    </div>
                </div>

                <!-- Admin Step 5 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #e11d48;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #e11d48; color: #ffffff !important;">5</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 5</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Monitoring BK & Export Rekap</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pantau Leaderboard Terlambat (seluruh sekolah), ekspor rekap PDF & Excel per kelas, dan kirim pesan WA ke orang tua.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex flex-col gap-1.5">
                        <a href="{{ route('reports.rekap') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Rekap & WA Ortu &rarr;</a>
                        <a href="{{ route('reports.leaderboard') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Leaderboard Terlambat &rarr;</a>
                    </div>
                </div>

            </div>

            <!-- Fitur Tambahan Enterprise & Keamanan -->
            <div class="page-card p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Fitur Unggulan Enterprise & Otomatisasi Sistem</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">📜 Audit Trail / Activity Log (`/activity-logs`)</span>
                        <p class="text-slate-500 leading-relaxed">Mencatat riwayat pengubahan, pembuatan, dan penghapusan data secara detail termasuk perbandingan data lama vs baru, IP address, dan User Agent.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">🗓️ Kalender Libur Sekolah (`/holidays`) & Hari Efektif</span>
                        <p class="text-slate-500 leading-relaxed">Kelola agenda libur sekolah & centang Hari Sekolah Efektif (Sekolah Umum 5/6 hari & Pesantren Jumat Libur). Scan RFID & Auto-Alpha otomatis libur pada hari non-efektif.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">🤖 Auto-Mark Alpha (`attendance:auto-alpha`)</span>
                        <p class="text-slate-500 leading-relaxed">Perintah scheduler otomatis (jam 17:00 / setelah jam pulang) yang secara otomatis menandai siswa aktif tanpa scan sebagai Alpha pada hari sekolah efektif.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">👔 Role Kepala Sekolah (`kepala_sekolah`)</span>
                        <p class="text-slate-500 leading-relaxed">Akses eksekutif view-only untuk memantau statistik kehadiran seluruh kelas, laporan rekapitulasi, dan leaderboard tanpa batasan wali kelas.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">🏥 Health Check Endpoint (`/api/health`)</span>
                        <p class="text-slate-500 leading-relaxed">Endpoint JSON real-time untuk memantau kesehatan koneksi database MySQL, operasional Cache, dan versi aplikasi.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <span class="font-bold text-indigo-600 block">⚡ Form Konfigurasi Multi-Hari & Rate Limit (`/settings/school`)</span>
                        <p class="text-slate-500 leading-relaxed">Admin dapat memilih multi-checkbox Hari Efektif Sekolah & mengatur batas request rate limit API pihak ketiga langsung via antarmuka web.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tab 2: Guru / Wali Kelas -->
        <div x-show="activeTab === 'guru'" x-transition class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Guru Step 1 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #4f46e5;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #4f46e5; color: #ffffff !important;">1</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 1</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Pantau Dashboard Kelas Binaan</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Setelah login, Dashboard Anda otomatis ter-filter menampilkan statistik total siswa, jumlah hadir, terlambat, dan belum absen dari **kelas binaan Anda saja**.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Dashboard Kelas &rarr;</a>
                    </div>
                </div>

                <!-- Guru Step 2 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #2563eb;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #2563eb; color: #ffffff !important;">2</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 2</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Input Presensi Harian Manual</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Untuk siswa yang tidak tap RFID atau perlu keterangan khusus. Mendukung <strong>7 status terpisah</strong>:
                            <span class="inline-flex flex-wrap gap-1 mt-1">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700">Izin</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-cyan-100 text-cyan-700">Pulang Cepat</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-teal-100 text-teal-700">Dispensasi</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">Sakit</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Alpha</span>
                            </span><br>
                            Gunakan <strong>Select2 multi-select</strong> untuk memilih banyak siswa sekaligus — cocok untuk input dispensasi lomba atau izin massal.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('attendances.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Input Presensi Manual &rarr;</a>
                    </div>
                </div>

                <!-- Guru Step 3 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #d97706;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #d97706; color: #ffffff !important;">3</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 3</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Kirim Rekap WA ke Orang Tua</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Buka menu **Rekap + WA Ortu** atau halaman **Detail Profil Siswa** untuk mengirim pesan notifikasi WhatsApp berisi rincian kehadiran anak ke orang tua dengan 1 klik.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('reports.rekap') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Kirim Pesan WA Ortu &rarr;</a>
                    </div>
                </div>

                <!-- Guru Step 4 -->
                <div class="page-card p-5 space-y-3 flex flex-col justify-between" style="border-top: 4px solid #059669;">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shadow-sm shrink-0" style="background-color: #059669; color: #ffffff !important;">4</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Langkah 4</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Download Rekap Kelas Excel & PDF</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Buka menu **Data Kelas** &rarr; klik **Lihat Siswa** untuk mengunduh file rekapitulasi kehadiran bulanan kelas Anda dalam format Excel (.xlsx) atau PDF.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <a href="{{ route('classes.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Download Rekap Kelas &rarr;</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Detail Tanya Jawab & Solusi Masalah FAQ -->
        <div class="page-card p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Tanya Jawab & Pertanyaan Umum (FAQ)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pertanyaan yang sering ditanyakan seputar pengoperasian sistem RFID dan hak akses.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1.5">
                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <span class="text-indigo-600 font-extrabold text-sm">Q:</span> Bagaimana cara membuat akun Admin baru?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Administrator dapat membuat akun Admin baru melalui menu <strong class="text-slate-800">Manajemen Pengguna</strong> (`/users`) atau via terminal perintah: <span class="font-mono text-[11px] bg-slate-200 px-1.5 py-0.5 rounded text-slate-800">php artisan make:admin</span>.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1.5">
                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <span class="text-indigo-600 font-extrabold text-sm">Q:</span> Mengapa Guru Wali Kelas hanya melihat data kelasnya saja?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sistem menerapkan <strong class="text-slate-800">Class-Level Authorization Scoping</strong> agar data antar-kelas terisolasi dengan aman dan guru wali kelas fokus mengurus siswa kelas binaannya.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1.5">
                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <span class="text-indigo-600 font-extrabold text-sm">Q:</span> Bagaimana cara menghubungkan Reader RFID ke Komputer?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Gunakan reader USB RFID 125kHz / 13.56MHz standar (Emulation Keyboard Mode). Sambungkan ke port USB komputer Kiosk, buka halaman Kiosk, lalu tap kartu untuk membaca UID.
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1.5">
                    <h4 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <span class="text-indigo-600 font-extrabold text-sm">Q:</span> Bagaimana cara mengirim WA ke Orang Tua?
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Buka menu <strong class="text-slate-800">Rekap + WA Ortu</strong> atau **Detail Siswa**, lalu klik ikon WhatsApp untuk membuka aplikasi WA dengan pesan notifikasi yang sudah terformat otomatis.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
