<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kalender Libur Sekolah</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Pengelolaan hari libur nasional & agenda libur sekolah.</p>
            </div>
        </div>
    </x-slot>

    <!-- Alert Catatan Hari Efektif Sekolah -->
    <div class="mb-5 p-4 rounded-xl border border-indigo-100 bg-indigo-50/70 flex items-start gap-3 text-xs text-indigo-900 shadow-sm">
        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="space-y-1">
            <span class="font-extrabold text-indigo-950 block">📌 Catatan Konfigurasi Libur Rutin Mingguan:</span>
            <p class="leading-relaxed text-indigo-800/90">
                Pengaturan hari sekolah efektif mingguan (misal **5 Hari Kerja**, **6 Hari Kerja**, maupun **Pesantren / Sekolah Islam yang Libur di Hari Jumat**) dikonfigurasi melalui menu <a href="{{ route('settings.school') }}#hari-efektif" class="font-bold text-indigo-600 underline hover:text-indigo-800">Pengaturan Sekolah &rarr; Tab Hari Efektif</a>. Agenda pada kalender di halaman ini khusus untuk mencatat hari libur nasional atau libur khusus sekolah.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Libur -->
        <div class="page-card p-6 h-fit">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Tambah Hari Libur</span>
            </h2>
            <form method="POST" action="{{ route('holidays.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Agenda / Hari Libur</label>
                    <input type="text" name="nama_libur" required placeholder="Contoh: Idul Fitri 1447 H" class="form-input text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required class="form-input text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..." class="form-input text-sm"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">
                    <span>Simpan Hari Libur</span>
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Libur -->
        <div class="lg:col-span-2 space-y-4">
            <div class="page-card">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <form method="GET" action="{{ route('holidays.index') }}" class="flex-1 w-full">
                        <div class="relative">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Cari nama hari libur..."
                                   style="padding-left: 40px !important;"
                                   class="form-input w-full text-xs sm:text-sm"
                                   oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Agenda Libur</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Rentang Tanggal</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($holidays as $h)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $h->nama_libur }}</div>
                                        <div class="text-xs text-slate-500">{{ $h->keterangan ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-600">
                                        <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-200 font-bold">
                                            {{ $h->tanggal_mulai->format('d M Y') }} - {{ $h->tanggal_selesai->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-1">
                                        <button type="button" 
                                                onclick="openEditHolidayModal('{{ $h->id }}', '{{ addslashes($h->nama_libur) }}', '{{ $h->tanggal_mulai->format('Y-m-d') }}', '{{ $h->tanggal_selesai->format('Y-m-d') }}', '{{ addslashes($h->keterangan ?? '') }}')" 
                                                class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition" title="Edit Agenda">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('holidays.destroy', $h) }}" onsubmit="confirmDelete(event, 'Hapus jadwal libur ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm">
                                        Belum ada data agenda libur sekolah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($holidays->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $holidays->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit Hari Libur -->
    <div id="editHolidayModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4 border border-slate-200">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Edit Agenda Libur Sekolah</h3>
                <button type="button" onclick="closeEditHolidayModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editHolidayForm" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Agenda / Hari Libur</label>
                    <input type="text" name="nama_libur" id="edit_nama_libur" required class="form-input text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required class="form-input text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required class="form-input text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="edit_keterangan" rows="2" class="form-input text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeEditHolidayModal()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Update Hari Libur</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditHolidayModal(id, nama, mulai, selesai, ket) {
            document.getElementById('editHolidayForm').action = '/holidays/' + id;
            document.getElementById('edit_nama_libur').value = nama;
            document.getElementById('edit_tanggal_mulai').value = mulai;
            document.getElementById('edit_tanggal_selesai').value = selesai;
            document.getElementById('edit_keterangan').value = ket;
            document.getElementById('editHolidayModal').classList.remove('hidden');
        }

        function closeEditHolidayModal() {
            document.getElementById('editHolidayModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>
