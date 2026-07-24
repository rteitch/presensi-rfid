<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Siswa Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Isi data identitas siswa dan mapping kartu RFID.</p>
            </div>
            <a href="{{ route('students.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="page-card p-6 md:p-8">
            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Sisi Kiri: Identitas Utama Siswa -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Identitas Siswa</h3>

                        <div>
                            <label class="form-label">NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nis" value="{{ old('nis') }}" required class="form-input font-mono" placeholder="cth. 2025001">
                            @error('nis') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required class="form-input" placeholder="Nama lengkap siswa">
                            @error('nama') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Kelas <span class="text-rose-500">*</span></label>
                            <select name="class_id" required class="form-input">
                                <option value="">— Pilih Kelas —</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('class_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Foto Profil Siswa (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="form-input text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('foto') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Status Siswa</label>
                            <select name="status" class="form-input">
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Non-aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sisi Kanan: RFID & Data Wali -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">RFID & Orang Tua</h3>

                        <!-- RFID Box -->
                        <div class="p-4 bg-indigo-50/60 border border-indigo-100 rounded-xl space-y-2">
                            <label class="form-label text-indigo-900 font-bold mb-0">RFID Card UID (Opsional)</label>
                            <p class="text-xs text-indigo-700">Fokuskan kursor pada kotak input ini, lalu tap kartu RFID pada USB Reader untuk mengisi UID secara otomatis.</p>
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" placeholder="Tap kartu RFID atau ketik manual UID..." class="form-input font-mono text-indigo-950 font-bold border-indigo-200">
                            @error('rfid_uid') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" class="form-input" placeholder="Nama orang tua/wali">
                        </div>

                        <div>
                            <label class="form-label">No HP Ortu / WhatsApp (Notifikasi WA)</label>
                            <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" placeholder="cth. 081234567890" class="form-input font-mono">
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('students.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
