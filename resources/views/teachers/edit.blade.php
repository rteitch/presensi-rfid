<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Data Guru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Ubah informasi guru dan data mengajar.</p>
            </div>
            <a href="{{ route('teachers.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="page-card p-6 md:p-8">
            <form method="POST" action="{{ route('teachers.update', $teacher) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Sisi Kiri: Identitas Utama -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Identitas Utama</h3>

                        <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <img src="{{ $teacher->foto_url }}" alt="{{ $teacher->nama }}" class="w-14 h-14 rounded-full object-cover border-2 border-indigo-200 shadow-sm shrink-0">
                            <div class="flex-1">
                                <label class="form-label mb-1">Ganti Foto Profil Guru</label>
                                <input type="file" name="foto" accept="image/*" class="form-input text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('foto') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label">NIP (Nomor Induk Pegawai) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" required class="form-input font-mono">
                            @error('nip') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Nama Lengkap Guru <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $teacher->nama) }}" required class="form-input">
                            @error('nama') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Status Guru</label>
                            <select name="status" class="form-input">
                                <option value="aktif" {{ old('status', $teacher->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $teacher->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Kontak & Mengajar -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Kontak & Mengajar</h3>

                        <div>
                            <label class="form-label">Email Login Akun (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="form-input font-mono">
                            @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $teacher->no_hp) }}" class="form-input font-mono">
                            @error('no_hp') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Mata Pelajaran / Bidang Mengajar</label>
                            <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran', $teacher->mata_pelajaran) }}" class="form-input">
                            @error('mata_pelajaran') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('teachers.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
