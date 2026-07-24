<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Kelas Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Buat data kelas baru dan tentukan wali kelas.</p>
            </div>
            <a href="{{ route('classes.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="page-card p-6 md:p-8">
            <form method="POST" action="{{ route('classes.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="Contoh: VII-A atau X-IPA-1" required class="form-input">
                        @error('nama_kelas') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label">Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <select name="academic_year_id" required class="form-input">
                            <option value="">— Pilih Tahun Ajaran —</option>
                            @foreach($academicYears as $y)
                                <option value="{{ $y->id }}" {{ old('academic_year_id') == $y->id || $y->is_active ? 'selected' : '' }}>
                                    {{ $y->nama }} {{ $y->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Wali Kelas (Guru)</label>
                        <select name="wali_kelas_id" class="form-input">
                            <option value="">— Pilih Wali Kelas —</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}" {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>{{ $g->name }} ({{ $g->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('classes.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
