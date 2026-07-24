<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('integrations.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Integrasi: {{ $integration->nama_aplikasi }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi atau status aktif integrasi aplikasi eksternal ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-4">
        {{-- Tampilkan API Key saat ini --}}
        <div class="p-4 bg-slate-900 rounded-xl border border-slate-700 space-y-2">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">API Key Aktif Saat Ini</p>
            <div class="flex items-center gap-2">
                <code class="font-mono text-sm text-emerald-400 flex-1 break-all">{{ $integration->api_key }}</code>
                <button onclick="copyToClipboard('{{ $integration->api_key }}', this)"
                        class="p-2 text-slate-400 hover:text-emerald-400 transition shrink-0" title="Salin API Key">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
            <p class="text-xs text-slate-500">
                Terakhir digunakan:
                <span class="{{ $integration->last_used_at ? 'text-emerald-400' : 'text-slate-500' }} font-semibold">
                    {{ $integration->last_used_at ? $integration->last_used_at->translatedFormat('d M Y H:i') : 'Belum pernah digunakan' }}
                </span>
            </p>
        </div>

        <div class="page-card p-6">
            <form method="POST" action="{{ route('integrations.update', $integration) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label required">Nama Aplikasi</label>
                    <input type="text" name="nama_aplikasi" value="{{ old('nama_aplikasi', $integration->nama_aplikasi) }}"
                           class="form-input" required>
                    @error('nama_aplikasi') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Deskripsi / Tujuan Integrasi</label>
                    <textarea name="deskripsi" rows="3" class="form-input">{{ old('deskripsi', $integration->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label required">Status Akses API</label>
                    <select name="is_active" class="form-input">
                        <option value="1" {{ old('is_active', $integration->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>✅ Aktif — Aplikasi boleh mengakses API</option>
                        <option value="0" {{ old('is_active', $integration->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>🔴 Non-aktif — Blokir akses API sementara</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('integrations.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => { btn.innerHTML = orig; }, 1800);
            });
        }
    </script>
</x-app-layout>
