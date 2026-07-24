<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('integrations.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Integrasi API Baru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Daftarkan aplikasi eksternal yang akan mengambil data presensi dari RTH NEXUS.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-4">
        {{-- Info Panduan --}}
        <div class="rounded-xl p-4 border border-amber-200 bg-amber-50/60 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <p class="text-xs font-bold text-amber-800">Simpan API Key Setelah Membuat Integrasi!</p>
                <p class="text-xs text-amber-700 leading-relaxed mt-0.5">API Key hanya ditampilkan sekali setelah pendaftaran. Pastikan Anda menyalinnya dan memberikannya kepada developer aplikasi yang terintegrasi.</p>
            </div>
        </div>

        <div class="page-card p-6">
            <form method="POST" action="{{ route('integrations.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label required">Nama Aplikasi</label>
                    <input type="text" name="nama_aplikasi" value="{{ old('nama_aplikasi') }}"
                           placeholder="Contoh: SIM Akademik Sekolah, Aplikasi Mobile Ortu, Sistem SPP"
                           class="form-input" required>
                    @error('nama_aplikasi') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Deskripsi / Tujuan Integrasi</label>
                    <textarea name="deskripsi" rows="3"
                              placeholder="Contoh: Aplikasi ini mengambil data rekap presensi bulanan untuk ditampilkan di portal orang tua."
                              class="form-input">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Info Endpoint --}}
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <p class="text-xs font-bold text-slate-700 mb-2">📡 Endpoint API yang Bisa Diakses:</p>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-mono">GET</span>
                            <code class="text-xs font-mono text-slate-700">/api/v1/attendances/rekap?bulan=2026-07&class_id=1</code>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-mono">GET</span>
                            <code class="text-xs font-mono text-slate-700">/api/v1/students/{id}/history?bulan=2026-07</code>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">Header wajib: <code class="bg-slate-200 px-1 rounded text-slate-700">X-API-Key: &lt;API_KEY&gt;</code></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('integrations.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Generate API Key & Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
