<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Presensi</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-normal">
                    Selamat pagi, <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span> &bull; {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('reports.leaderboard') }}" target="_blank" class="btn-secondary text-xs px-3 py-2">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span>Leaderboard Kedisiplinan</span>
                </a>
                <a href="{{ route('guide') }}" class="btn-secondary text-xs px-3 py-2">
                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Panduan Aplikasi</span>
                </a>
                <a href="{{ route('attendances.index') }}" class="btn-secondary text-xs px-3 py-2">
                    <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Presensi Harian</span>
                </a>
                <a href="{{ route('kiosk.scan') }}" target="_blank" class="btn-primary text-xs px-3 py-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Mode Kiosk Scanner</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Stat Cards Grid (Responsive 5-Col Grid Layout) -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">

            <!-- Card 1: Total Siswa -->
            <div class="stat-card col-span-2 sm:col-span-1 lg:col-span-1 flex flex-col justify-between relative overflow-hidden">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Terdaftar</span>
                        <div class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ number_format($total_siswa) }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-emerald-600 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Sistem Presensi Aktif
                    </span>
                    <span class="text-slate-400 font-medium">{{ $schoolSettings['school_name'] ?? 'Sekolah' }}</span>
                </div>
            </div>

            <!-- Card 2: Hadir Tepat Waktu -->
            <div class="stat-card flex flex-col justify-between border-l-4 border-l-emerald-500">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Hadir</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 mt-2 tracking-tight">{{ number_format($hadir_hari_ini) }}</div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Tepat waktu hari ini</p>
            </div>

            <!-- Card 3: Terlambat -->
            <div class="stat-card flex flex-col justify-between border-l-4 border-l-amber-500">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Terlambat</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 mt-2 tracking-tight">{{ number_format($terlambat_hari_ini) }}</div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Lewat jam masuk</p>
            </div>

            <!-- Card 4: Izin / Sakit -->
            <div class="stat-card flex flex-col justify-between border-l-4 border-l-blue-500">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Izin / Sakit</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 mt-2 tracking-tight">{{ number_format($izin_sakit_alpha) }}</div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Dengan keterangan</p>
            </div>

            <!-- Card 5: Belum Absen -->
            <div class="stat-card flex flex-col justify-between border-l-4 border-l-slate-400">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Belum Absen</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 mt-2 tracking-tight">{{ number_format($belum_presensi) }}</div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Belum scan RFID</p>
            </div>

        </div>

        <!-- 2 Column Layout: Progress Breakdown + Weekly Chart -->
        @php
            $total = max($total_siswa, 1);
            $pctHadir = round(($hadir_hari_ini / $total) * 100);
            $pctTerlambat = round(($terlambat_hari_ini / $total) * 100);
            $pctIzin = round(($izin_sakit_alpha / $total) * 100);
            $pctBelum = round(($belum_presensi / $total) * 100);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left: Attendance Progress Breakdown (7 Cols) -->
            <div class="lg:col-span-7 page-card p-6 flex flex-col justify-between space-y-5">
                <div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Persentase Kehadiran Hari Ini</h3>
                            <p class="text-xs text-slate-500 mt-0.5 font-normal">Ringkasan status kehadiran dari {{ number_format($total_siswa) }} siswa terdaftar.</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-indigo-600 tracking-tight">{{ $pctHadir }}%</div>
                            <div class="text-[11px] text-slate-400 font-medium">Tingkat Kehadiran</div>
                        </div>
                    </div>

                    <!-- Slim Segmented Progress Bar -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden flex mt-4">
                        <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ $pctHadir }}%" title="Hadir: {{ $pctHadir }}%"></div>
                        <div class="bg-amber-400 h-full transition-all duration-500" style="width: {{ $pctTerlambat }}%" title="Terlambat: {{ $pctTerlambat }}%"></div>
                        <div class="bg-blue-400 h-full transition-all duration-500" style="width: {{ $pctIzin }}%" title="Izin/Sakit: {{ $pctIzin }}%"></div>
                        <div class="bg-slate-200 h-full transition-all duration-500" style="width: {{ $pctBelum }}%" title="Belum Absen: {{ $pctBelum }}%"></div>
                    </div>
                </div>

                <!-- Legend Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2">
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-semibold text-slate-700">Hadir</span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ number_format($hadir_hari_ini) }}</div>
                        <div class="text-[10px] text-slate-400">{{ $pctHadir }}% dari total</div>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="text-xs font-semibold text-slate-700">Terlambat</span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ number_format($terlambat_hari_ini) }}</div>
                        <div class="text-[10px] text-slate-400">{{ $pctTerlambat }}% dari total</div>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                            <span class="text-xs font-semibold text-slate-700">Izin / Sakit</span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ number_format($izin_sakit_alpha) }}</div>
                        <div class="text-[10px] text-slate-400">{{ $pctIzin }}% dari total</div>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <span class="text-xs font-semibold text-slate-700">Belum Absen</span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ number_format($belum_presensi) }}</div>
                        <div class="text-[10px] text-slate-400">{{ $pctBelum }}% dari total</div>
                    </div>
                </div>
            </div>

            <!-- Right: Weekly Trend Chart (5 Cols) -->
            <div class="lg:col-span-5 page-card p-6 flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Tren Kehadiran 7 Hari</h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-normal">Riwayat presensi siswa seminggu terakhir.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">Seminggu Terakhir</span>
                </div>

                <!-- Bar Chart Container with Background Grid Lines -->
                <div class="relative h-44 pt-6 pb-2 px-2 flex items-end justify-between gap-2 border-b border-slate-200">
                    <!-- Background Horizontal Grid Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-6 pt-6">
                        <div class="w-full border-b border-slate-100"></div>
                        <div class="w-full border-b border-slate-100"></div>
                        <div class="w-full border-b border-slate-100"></div>
                    </div>

                    @foreach($weekly_data as $day)
                        @php
                            $maxVal = is_numeric($max_weekly) && $max_weekly > 0 ? (int)$max_weekly : 1;
                            $hasData = $day['total'] > 0;
                            $heightPct = $hasData ? round(($day['total'] / $maxVal) * 100) : 8;
                            $barHeight = max(8, min(100, $heightPct));
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5 group h-full justify-end z-10">
                            <!-- Count Badge above Bar -->
                            <span class="text-[10px] font-bold text-slate-600 transition-opacity">
                                {{ $day['total'] }}
                            </span>

                            <!-- Bar Column -->
                            <div class="w-full max-w-[28px] rounded-t-lg transition-all duration-300 relative overflow-hidden flex flex-col justify-end shadow-sm {{ $hasData ? ($day['is_today'] ? 'bg-gradient-to-t from-indigo-700 to-indigo-500' : 'bg-gradient-to-t from-indigo-500 to-indigo-400') : 'bg-slate-100 border border-slate-200/60' }}"
                                 style="height: {{ $barHeight }}%;">
                                @if($day['terlambat'] > 0 && $day['total'] > 0)
                                    @php $terlambatPct = round(($day['terlambat'] / $day['total']) * 100); @endphp
                                    <div class="bg-amber-400 w-full" style="height: {{ $terlambatPct }}%;" title="Terlambat: {{ $day['terlambat'] }}"></div>
                                @endif
                            </div>

                            <!-- Day Label -->
                            <span class="text-[11px] font-medium mt-1 {{ $day['is_today'] ? 'text-indigo-600 font-bold' : 'text-slate-500' }}">
                                {{ $day['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-indigo-600 inline-block"></span> Hadir</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-amber-400 inline-block"></span> Terlambat</span>
                    </div>
                    <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-semibold flex items-center gap-1">
                        <span>Laporan Lengkap</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Recent Activity Feed Table -->
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="font-bold text-slate-900 text-sm">Aktivitas Scan RFID Terbaru</h3>
                </div>
                <a href="{{ route('attendances.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1 transition">
                    <span>Lihat Semua Presensi</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Waktu Scan</th>
                            <th>Siswa / Kartu RFID</th>
                            <th>Status Pemindaian</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_logs as $log)
                            @php
                                $student = $log->student;
                            @endphp
                            <tr class="table-row">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="font-mono text-xs font-bold text-slate-800">{{ $log->scanned_at->format('H:i:s') }}</span>
                                        <span class="text-slate-400 text-xs font-normal">&bull; {{ $log->scanned_at->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($student)
                                            <img src="{{ $student->foto_url }}" alt="{{ $student->nama }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0">
                                            <div>
                                                <div class="font-semibold text-slate-900 text-xs">{{ $student->nama }}</div>
                                                <div class="font-mono text-[11px] text-slate-400">{{ $log->rfid_uid }}</div>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 font-bold text-xs">
                                                ?
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-700 text-xs italic">Kartu Tidak Terdaftar</div>
                                                <div class="font-mono text-[11px] text-slate-400">{{ $log->rfid_uid }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($log->is_valid)
                                        <span class="badge badge-green">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Berhasil
                                        </span>
                                    @else
                                        <span class="badge badge-red">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Gagal Scan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-xs text-slate-600 font-normal">{{ $log->keterangan }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm font-medium">Belum ada riwayat pemindaian RFID hari ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
