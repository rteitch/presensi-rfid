<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-50 border border-indigo-100 rounded-xl shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Jam Masuk & Presensi</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Konfigurasi jam masuk, jam pulang, dan toleransi keterlambatan siswa.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Konfigurasi Jam Presensi -->
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Jam Masuk, Pulang & Toleransi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Siswa yang tap RFID setelah (Jam Masuk + Toleransi) otomatis ditandai Terlambat.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="form-label">Tahun Ajaran Aktif <span class="text-rose-500">*</span></label>
                        <select name="academic_year_id" required class="form-input font-medium text-slate-800">
                            @foreach($academicYears as $y)
                                <option value="{{ $y->id }}" {{ ($activeYear && $activeYear->id == $y->id) ? 'selected' : '' }}>
                                    {{ $y->nama }} ({{ $y->tanggal_mulai }} s/d {{ $y->tanggal_selesai }}) {{ $y->is_active ? '— [AKTIF]' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label class="form-label">Jam Masuk Sekolah <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_masuk"
                                   value="{{ old('jam_masuk', $setting ? \Carbon\Carbon::parse($setting->jam_masuk)->format('H:i') : '07:00') }}"
                                   required class="form-input font-mono text-center text-lg font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="form-label">Jam Pulang Sekolah <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_pulang"
                                   value="{{ old('jam_pulang', $setting ? \Carbon\Carbon::parse($setting->jam_pulang)->format('H:i') : '15:00') }}"
                                   required class="form-input font-mono text-center text-lg font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="form-label">Toleransi (Menit) <span class="text-rose-500">*</span></label>
                            <input type="number" min="0" max="120" name="toleransi_menit"
                                   value="{{ old('toleransi_menit', $setting ? $setting->toleransi_menit : 15) }}"
                                   required class="form-input text-center text-lg font-bold text-indigo-700">
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-900">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-xs">
                            <strong class="text-amber-950 font-bold">Contoh Penghitungan Keterlambatan:</strong><br>
                            Jam Masuk <code class="bg-amber-100 text-amber-900 px-1.5 py-0.5 rounded font-mono font-bold">07:00</code> + Toleransi <code class="bg-amber-100 text-amber-900 px-1.5 py-0.5 rounded font-mono font-bold">15 menit</code>:
                            <ul class="mt-1.5 space-y-0.5">
                                <li>• Tap <strong>06:00 – 07:15</strong> → <span class="text-emerald-700 font-bold">Hadir Tepat Waktu</span></li>
                                <li>• Tap <strong>07:16 ke atas</strong> → <span class="text-amber-700 font-bold">Terlambat</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Aturan Jam</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tambah Tahun Ajaran -->
        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Tambah Periode Tahun Ajaran Baru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tambahkan periode akademik untuk mengelompokkan presensi siswa.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('settings.academic-year') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-5 items-end">
                    @csrf
                    <div>
                        <label class="form-label">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" placeholder="cth. 2026/2027" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_mulai" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_selesai" required class="form-input">
                    </div>
                    <div class="sm:col-span-3 flex justify-end pt-2">
                        <button type="submit" class="btn-secondary">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Tahun Ajaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
