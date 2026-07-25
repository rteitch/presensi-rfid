<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tong Sampah Data Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Daftar siswa yang telah dihapus sementara. Anda dapat memulihkan kembali atau menghapus permanen.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('students.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Data Siswa</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tab Switcher Navigation -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <a href="{{ route('students.index') }}"
               style="background-color: #f1f5f9; color: #475569;"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 hover:bg-slate-200">
                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Siswa Aktif</span>
            </a>
            <a href="{{ route('students.trashed') }}"
               style="background-color: #e11d48 !important; color: #ffffff !important;"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 shadow-sm">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span style="color: #ffffff !important;">Tong Sampah / Terhapus</span>
                <span style="background-color: #ffffff !important; color: #be123c !important;" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold shadow-sm">{{ $trashedCount }}</span>
            </a>
        </div>

        <div class="page-card">
            <!-- Search & Filter Bar -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <form method="GET" action="{{ route('students.trashed') }}" class="flex-1">
                        <div class="relative flex items-center">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Cari siswa terhapus (NIS / Nama / RFID)..."
                                   style="padding-left: 40px !important; padding-right: 40px !important;"
                                   class="form-input w-full"
                                   oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                            @if(!empty($search))
                                <a href="{{ route('students.trashed') }}" style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition p-1" title="Hapus Pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                    <div class="text-xs text-slate-500 font-bold bg-rose-50 text-rose-700 px-3 py-2 rounded-xl border border-rose-200 text-center sm:text-left whitespace-nowrap">Total Terhapus: {{ $students->total() }} Siswa</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Foto</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Waktu Dihapus</th>
                            <th class="text-right">Aksi Permintaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $s)
                            <tr class="table-row opacity-80 hover:opacity-100 transition">
                                <td>
                                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 shadow-sm shrink-0 grayscale">
                                </td>
                                <td>
                                    <span class="font-mono font-bold text-slate-800 text-xs">{{ $s->nis }}</span>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-900 text-sm line-through decoration-rose-400">{{ $s->nama }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium">Ortu: {{ $s->nama_ortu ?: '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-gray">{{ $s->schoolClass->nama_kelas ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="text-xs font-mono text-slate-500">{{ $s->deleted_at ? $s->deleted_at->translatedFormat('d M Y H:i') : '-' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Tombol Restore (Pulihkan) --}}
                                        <form method="POST" action="{{ route('students.restore', $s->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-lg transition shadow-sm whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Pulihkan</span>
                                            </button>
                                        </form>

                                        {{-- Tombol Force Delete (Hapus Permanen) --}}
                                        <form method="POST" action="{{ route('students.force-delete', $s->id) }}" class="inline"
                                              onsubmit="confirmDelete(event, 'PERINGATAN KRITIS: Data siswa {{ $s->nama }} akan DIHAPUS PERMANEN dari database beserta foto profil dan tidak dapat dikembalikan lagi. Lanjutkan?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-lg transition shadow-sm whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus Permanen</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span class="text-sm font-semibold text-slate-600">Tong Sampah Kosong</span>
                                        <span class="text-xs text-slate-400">Tidak ada data siswa yang pernah dihapus sementara.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
