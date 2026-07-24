<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Pengguna Baru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Buat akun login baru untuk Admin atau Guru / Wali Kelas.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="page-card p-6">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Drs. H. Ahmad Subagyo" class="form-input" required>
                    @error('name') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label required">Alamat Email (Username Login)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin2@sekolah.sch.id" class="form-input font-mono" required>
                    @error('email') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Role / Hak Akses -->
                <div>
                    <label class="form-label required">Role / Hak Akses</label>
                    <select name="role" class="form-input" required>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ADMINISTRATOR — Full Akses (Semua Menu & Pengaturan)</option>
                        <option value="guru" {{ old('role', 'guru') === 'guru' ? 'selected' : '' }}>GURU / WALI KELAS — Terbatas (Hanya Kelas Binaan & Laporan)</option>
                    </select>
                    @error('role') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Grid Password & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <label class="form-label required">Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-input" required>
                        @error('password') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label required">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang password" class="form-input" required>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
