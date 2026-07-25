<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    @if(!empty($isGuru))
                        Data Siswa {{ $managedClassName ? "({$managedClassName})" : 'Kelas Binaan' }}
                    @else
                        Data Siswa & Kartu RFID
                    @endif
                </h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">
                    @if(!empty($isGuru))
                        Daftar siswa terdaftar di kelas binaan Anda {{ $managedClassName ? "({$managedClassName})" : '' }}.
                    @else
                        Kelola data siswa, mapping kartu RFID, dan foto profil.
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                @role('admin')
                <a href="{{ route('students.export') }}" class="btn-secondary text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100" title="Export data siswa ke Excel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export</span>
                </a>
                <a href="{{ route('students.import') }}" class="btn-secondary text-blue-700 border-blue-200 bg-blue-50 hover:bg-blue-100" title="Import data siswa dari Excel">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Import</span>
                </a>
                <a href="{{ route('students.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Siswa</span>
                </a>
                @endrole
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

        @role('admin')
        <!-- Tab Switcher Navigation -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <a href="{{ route('students.index') }}"
               style="background-color: #4f46e5 !important; color: #ffffff !important;"
               class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 shadow-sm">
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Siswa Aktif</span>
            </a>
            <a href="{{ route('students.trashed') }}"
               style="background-color: #f1f5f9; color: #475569;"
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 hover:bg-slate-200">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Tong Sampah / Terhapus</span>
                @if(!empty($trashedCount) && $trashedCount > 0)
                    <span style="background-color: #ffe4e6; color: #be123c; border: 1px solid #fecdd3;" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold">{{ $trashedCount }}</span>
                @endif
            </a>
        </div>
        @endrole

        <div class="page-card">
            <!-- Search & Filter Bar -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <form method="GET" action="{{ route('students.index') }}" class="flex-1">
                        <div class="relative flex items-center">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Ketik untuk mencari NIS, Nama Siswa, atau RFID UID..."
                                   style="padding-left: 40px !important; padding-right: 40px !important;"
                                   class="form-input w-full"
                                   oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                            @if(!empty($search))
                                <a href="{{ route('students.index') }}" style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition p-1" title="Hapus Pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                    <div class="text-xs text-slate-500 font-bold bg-slate-100 px-3 py-2 rounded-xl border border-slate-200 text-center sm:text-left whitespace-nowrap">Total: {{ $students->total() }} Siswa</div>
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
                            <th>Kartu RFID</th>
                            <th>Orang Tua / HP</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $s)
                            <tr class="table-row">
                                <td>
                                    <img src="{{ $s->foto_url }}" alt="{{ $s->nama }}" class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 shadow-sm shrink-0">
                                </td>
                                <td>
                                    <span class="font-mono font-bold text-slate-800 text-xs">{{ $s->nis }}</span>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-900 text-sm">{{ $s->nama }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-indigo">{{ $s->schoolClass->nama_kelas ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($s->rfid_uid)
                                        <span class="font-mono text-xs bg-slate-100 border border-slate-200 text-slate-800 px-2.5 py-1 rounded-lg font-bold">{{ $s->rfid_uid }}</span>
                                    @else
                                        <span class="text-rose-500 font-medium italic text-xs">Belum Mapping</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-xs font-semibold text-slate-800">{{ $s->nama_ortu ?? '-' }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $s->no_hp_ortu ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $s->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">
                                        {{ $s->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                 <td class="text-right">
                                     <div class="flex items-center justify-end gap-1.5">
                                         <a href="{{ route('students.show', $s) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200/80 rounded-lg transition">
                                             <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                             </svg>
                                             <span>Detail</span>
                                         </a>
                                         @role('admin')
                                         <a href="{{ route('students.edit', $s) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 rounded-lg transition">
                                             <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                             </svg>
                                             <span>Edit</span>
                                         </a>
                                         <form method="POST" action="{{ route('students.destroy', $s) }}" class="inline"
                                               onsubmit="confirmDelete(event, 'Yakin ingin menghapus data siswa {{ $s->nama }}?')">
                                             @csrf @method('DELETE')
                                             <button type="submit"
                                                     class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-lg transition">
                                                 <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                 </svg>
                                                 <span>Hapus</span>
                                             </button>
                                         </form>
                                         @endrole
                                     </div>
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-sm font-medium">Data siswa tidak ditemukan.</span>
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
