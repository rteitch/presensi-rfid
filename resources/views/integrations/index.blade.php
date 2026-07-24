<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Integrasi API Eksternal</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Kelola aplikasi pihak ke-3 yang terintegrasi untuk mengambil data presensi via REST API.</p>
            </div>
            <a href="{{ route('integrations.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Integrasi</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Info Card --}}
        <div class="rounded-xl p-4 border border-indigo-200 bg-indigo-50/60 flex items-start gap-3">
            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="space-y-0.5">
                <p class="text-xs font-bold text-indigo-800">Cara Menggunakan API Key</p>
                <p class="text-xs text-indigo-700 leading-relaxed">Setiap integrasi memiliki <span class="font-mono font-bold">API Key</span> unik. Sertakan pada header HTTP setiap request: <span class="font-mono bg-indigo-100 border border-indigo-200 px-1.5 py-0.5 rounded text-[11px] text-indigo-900">X-API-Key: &lt;API_KEY&gt;</span> untuk memanggil endpoint <span class="font-mono text-[11px] font-bold">GET /api/v1/attendances/rekap</span> atau <span class="font-mono text-[11px] font-bold">GET /api/v1/students/{id}/history</span>.</p>
            </div>
        </div>

        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <form method="GET" action="{{ route('integrations.index') }}" class="flex-1">
                        <div class="relative flex items-center">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Cari nama atau deskripsi aplikasi..."
                                   style="padding-left: 40px !important;"
                                   class="form-input"
                                   oninput="clearTimeout(window.st); window.st=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                        </div>
                    </form>
                    <div class="text-xs text-slate-500 font-bold bg-slate-100 px-3 py-2 rounded-xl border border-slate-200 whitespace-nowrap">
                        Total: {{ $integrations->total() }} Integrasi
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th>Nama Aplikasi</th>
                            <th>Deskripsi</th>
                            <th>API Key</th>
                            <th>Terakhir Digunakan</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($integrations as $i => $int)
                            <tr class="table-row">
                                <td class="text-center font-mono text-xs text-slate-400 font-bold">{{ $integrations->firstItem() + $i }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-100 to-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-slate-900 text-sm">{{ $int->nama_aplikasi }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs text-slate-500">{{ $int->deskripsi ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <code class="font-mono text-[11px] bg-slate-100 border border-slate-200 text-slate-700 px-2 py-1 rounded-lg max-w-[180px] truncate" title="{{ $int->api_key }}">
                                            {{ substr($int->api_key, 0, 16) }}...
                                        </code>
                                        <button onclick="copyToClipboard('{{ $int->api_key }}', this)"
                                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Salin API Key">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('integrations.regenerate', $int) }}"
                                              onsubmit="confirmDelete(event, 'Yakin regenerate API Key untuk {{ $int->nama_aplikasi }}? Key lama tidak bisa digunakan lagi.')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition" title="Regenerate API Key">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs {{ $int->last_used_at ? 'text-emerald-700 font-semibold' : 'text-slate-400' }}">
                                        {{ $int->last_used_at ? $int->last_used_at->diffForHumans() : 'Belum pernah' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $int->is_active ? 'badge-green' : 'badge-gray' }}">
                                        {{ $int->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('integrations.edit', $int) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('integrations.destroy', $int) }}" class="inline"
                                              onsubmit="confirmDelete(event, 'Hapus integrasi {{ $int->nama_aplikasi }}? Akses API akan langsung dicabut.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-lg transition">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <div>
                                            <p class="text-sm font-bold text-slate-500">Belum ada integrasi API terdaftar</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Klik "Tambah Integrasi" untuk mendaftarkan aplikasi pertama.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($integrations->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $integrations->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => { btn.innerHTML = orig; }, 1800);
            });
        }
    </script>
</x-app-layout>
