<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('classes.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Detail Kelas: {{ $class->nama_kelas }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar siswa dan statistik kehadiran bulanan.</p>
                </div>
            </div>
            <a href="{{ route('classes.edit', $class) }}" class="btn-secondary">
                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Kelas</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Info Kelas + Stat Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Info Kelas -->
            <div class="page-card p-5 sm:col-span-2 lg:col-span-1 flex flex-col justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="text-lg font-extrabold text-slate-900">{{ $class->nama_kelas }}</div>
                        <div class="text-xs text-slate-500">{{ $class->academicYear->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="text-xs text-slate-600 space-y-1 pt-1 border-t border-slate-100">
                    <div>Wali Kelas: <strong class="text-slate-900">{{ $class->waliKelas->name ?? 'Belum Ditentukan' }}</strong></div>
                    <div>Total Siswa: <strong class="text-indigo-700">{{ $totalSiswa }} orang</strong></div>
                </div>
            </div>

            <!-- Rata-rata Hadir -->
            <div class="page-card p-5 flex flex-col justify-between border-l-4 border-l-emerald-500">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Rata-rata Hadir</span>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $avgHadir }}<span class="text-base font-normal text-slate-500"> hari</span></div>
                <p class="text-[11px] text-slate-400 mt-1">Per siswa bulan ini</p>
            </div>

            <!-- Total Terlambat -->
            <div class="page-card p-5 flex flex-col justify-between border-l-4 border-l-amber-500">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Total Terlambat</span>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $totalTerlambat }}<span class="text-base font-normal text-slate-500"> kali</span></div>
                <p class="text-[11px] text-slate-400 mt-1">Seluruh siswa kelas ini</p>
            </div>

            <!-- Total Alpha -->
            <div class="page-card p-5 flex flex-col justify-between border-l-4 border-l-rose-500">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Total Alpha</span>
                <div class="text-3xl font-black text-slate-900 mt-2">{{ $totalAlpha }}<span class="text-base font-normal text-slate-500"> hari</span></div>
                <p class="text-[11px] text-slate-400 mt-1">Seluruh siswa kelas ini</p>
            </div>
        </div>

        <!-- Tabel Daftar Siswa -->
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Daftar Siswa & Rekap Presensi</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Klik <strong>Detail</strong> untuk melihat riwayat presensi lengkap per siswa.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <form method="GET" action="{{ route('classes.show', $class) }}" class="flex items-center gap-2">
                        <label class="text-xs text-slate-500 font-medium whitespace-nowrap">Bulan:</label>
                        <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" class="form-input text-xs py-1.5 px-3">
                    </form>
                    <a href="{{ route('classes.export-excel', $class) }}?bulan={{ $bulan }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition whitespace-nowrap"
                       title="Export Rekap Kelas ke Excel">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Excel</span>
                    </a>
                    <a href="{{ route('classes.export-pdf', $class) }}?bulan={{ $bulan }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition whitespace-nowrap"
                       title="Export Rekap Kelas ke PDF">
                        <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>PDF</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="text-center w-10">#</th>
                            <th>Siswa</th>
                            <th>NIS</th>
                            <th>Status</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Alpha</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $i => $s)
                            <tr class="table-row">
                                <td class="text-center text-xs text-slate-400 font-mono font-bold">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}"
                                             class="w-9 h-9 rounded-full object-cover border border-slate-200 shrink-0">
                                        <div class="font-semibold text-slate-900 text-sm">{{ $s->nama }}</div>
                                    </div>
                                </td>
                                <td><span class="font-mono text-xs font-bold text-slate-700">{{ $s->nis }}</span></td>
                                <td>
                                    <span class="badge {{ $s->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="font-bold text-emerald-700 text-sm">{{ $s->stat_hadir }}</span>
                                </td>
                                <td class="text-center">
                                    @if($s->stat_terlambat > 0)
                                        <span class="badge badge-amber font-bold">{{ $s->stat_terlambat }}x</span>
                                    @else
                                        <span class="text-slate-400 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="font-semibold text-blue-700 text-sm">{{ $s->stat_izin ?: '—' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="font-semibold text-indigo-700 text-sm">{{ $s->stat_sakit ?: '—' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($s->stat_alpha > 0)
                                        <span class="badge badge-red font-bold">{{ $s->stat_alpha }}x</span>
                                    @else
                                        <span class="text-slate-400 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('students.show', $s) }}?bulan={{ $bulan }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200/80 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-sm font-medium">Belum ada siswa di kelas ini.</span>
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
