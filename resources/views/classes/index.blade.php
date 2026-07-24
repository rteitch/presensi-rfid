<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    @if(!empty($isGuru))
                        Data Kelas Binaan {{ $managedClassName ? "({$managedClassName})" : '' }}
                    @else
                        Data Kelas
                    @endif
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    @if(!empty($isGuru))
                        Daftar kelas dan alokasi siswa yang Anda ampu.
                    @else
                        Kelola kelas dan alokasi wali kelas.
                    @endif
                </p>
            </div>
            @role('admin')
            <a href="{{ route('classes.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kelas
            </a>
            @endrole
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="page-card">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <form method="GET" action="{{ route('classes.index') }}" class="flex-1">
                        <div class="relative flex items-center">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Ketik untuk mencari nama kelas atau wali kelas..."
                                   style="padding-left: 40px !important; padding-right: 40px !important;"
                                   class="form-input"
                                   oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                            @if(request('search'))
                                <a href="{{ route('classes.index') }}" style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition p-1" title="Hapus Pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                    <div class="text-xs text-slate-500 font-bold bg-slate-100 px-3 py-2 rounded-xl border border-slate-200 text-center sm:text-left whitespace-nowrap">Total: {{ $classes->total() }} Kelas</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Wali Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Jumlah Siswa</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $c)
                            <tr class="table-row">
                                <td class="font-bold text-slate-900 text-base">{{ $c->nama_kelas }}</td>
                                <td class="text-slate-700 font-medium">{{ $c->waliKelas->name ?? 'Belum Ditentukan' }}</td>
                                <td><span class="badge badge-gray">{{ $c->academicYear->nama ?? '-' }}</span></td>
                                <td><span class="badge badge-indigo font-bold">{{ $c->students->count() }} Siswa</span></td>
                                 <td class="text-right">
                                     <div class="flex items-center justify-end gap-1.5">
                                         <a href="{{ route('classes.show', $c) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200/80 rounded-lg transition">
                                             <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                             </svg>
                                             <span>Lihat Siswa</span>
                                         </a>
                                         @role('admin')
                                         <a href="{{ route('classes.edit', $c) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 rounded-lg transition">
                                             <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                             </svg>
                                             <span>Edit</span>
                                         </a>
                                         <form method="POST" action="{{ route('classes.destroy', $c) }}" class="inline" onsubmit="confirmDelete(event, 'Yakin ingin menghapus data kelas {{ $c->nama_kelas }}?')">
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
                                <td colspan="5" class="py-12 text-center text-slate-400 text-sm">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($classes->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $classes->links() }}
                </div>
            @endif
        </div>
    </div>

    </x-app-layout>
