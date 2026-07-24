<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Audit Trail / Log Aktivitas</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Catatan riwayat aksi pengguna, perubahan data, dan jejak audit keamanan.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <div class="page-card">
            <!-- Filter Bar -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <div class="relative flex-1">
                        <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                               placeholder="Cari deskripsi, model, atau nama pengguna..."
                               style="padding-left: 40px !important; padding-right: 40px !important;"
                               class="form-input w-full text-xs sm:text-sm"
                               oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                        @if(!empty($search))
                            <a href="{{ route('activity-logs.index') }}" style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition p-1" title="Hapus Pencarian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                    </div>
                    <div class="w-full sm:w-48">
                        <select name="action" onchange="window.performAjaxSearch(this.form)" class="form-input text-xs w-full font-medium">
                            <option value="">Semua Aksi</option>
                            <option value="created" {{ ($actionFilter ?? '') === 'created' ? 'selected' : '' }}>Created (Tambah)</option>
                            <option value="updated" {{ ($actionFilter ?? '') === 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                            <option value="deleted" {{ ($actionFilter ?? '') === 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi Aktivitas</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                    {{ $log->created_at->format('d M Y, H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-xs">{{ $log->user->name ?? 'Sistem / Guest' }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ $log->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->action === 'created')
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-200">TAMBAH</span>
                                    @elseif($log->action === 'updated')
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg bg-amber-100 text-amber-700 border border-amber-200">UBAH</span>
                                    @elseif($log->action === 'deleted')
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg bg-rose-100 text-rose-700 border border-rose-200">HAPUS</span>
                                    @else
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg bg-slate-100 text-slate-700 border border-slate-200">{{ strtoupper($log->action) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-700">
                                    <div class="font-semibold text-slate-900">{{ $log->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    Belum ada catatan aktivitas sistem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
