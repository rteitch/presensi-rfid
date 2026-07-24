<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Guru Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Isi data identitas guru dan informasi mengajar.</p>
            </div>
            <a href="{{ route('teachers.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="page-card p-6 md:p-8">
            <form method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Sisi Kiri: Data Utama -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Identitas Utama</h3>

                        <div>
                            <label class="form-label">NIP (Nomor Induk Pegawai) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nip" value="{{ old('nip') }}" required class="form-input font-mono" placeholder="cth. 198501012010011001">
                            @error('nip') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Nama Lengkap Guru <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Nama lengkap beserta gelar">
                            @error('nama') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Foto Profil Guru (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="form-input text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('foto') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Status Guru</label>
                            <select name="status" class="form-input">
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Non-aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Kontak & Mengajar -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Kontak & Mengajar</h3>

                        <div>
                            <label class="form-label">Email Login Akun (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input font-mono" placeholder="guru@sekolah.sch.id">
                            <p class="text-xs text-slate-400 mt-1">Jika email diisi, akun login guru akan dibuatkan otomatis.</p>
                            @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="cth. 081234567890" class="form-input font-mono">
                            @error('no_hp') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Mata Pelajaran / Bidang Mengajar</label>
                            <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" class="form-input" placeholder="cth. Matematika, IPA, Bahasa Indonesia">
                            @error('mata_pelajaran') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('teachers.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Data Guru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
