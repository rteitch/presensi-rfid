<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Device RFID</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Kelola device scanner RFID, token autentikasi, dan status aktif.</p>
            </div>
            <a href="{{ route('devices.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Device</span>
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

        <!-- Penanda 2 Skenario Device -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-100 space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-600 text-white rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <h4 class="font-bold text-slate-800 text-xs">Mode 1: Kiosk Browser (USB Reader HID)</h4>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Dicolokkan ke Komputer/Laptop di gerbang. Reader USB bertindak seperti keyboard, otomatis membaca UID saat kartu di-tap pada halaman <strong class="text-indigo-700">/kiosk</strong>.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-violet-50/70 border border-violet-100 space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-violet-600 text-white rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                    </span>
                    <h4 class="font-bold text-slate-800 text-xs">Mode 2: Microcontroller IoT Box (ESP32 / NodeMCU)</h4>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Perangkat IoT mandiri dengan Wi-Fi/LAN + MFRC522. Token disisipkan di kodingan C++/Arduino pada header HTTP: <code class="font-mono text-[11px] bg-white px-1 py-0.5 rounded border border-violet-200 text-violet-800">X-Device-Token</code>.
                </p>
            </div>
        </div>

        <div class="page-card">
            <!-- Search & Filter Bar (Integrated Pill Style) -->
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
                    <form method="GET" action="{{ route('devices.index') }}" class="flex-1">
                        <div class="relative flex items-center">
                            <svg style="width:16px;height:16px; top: 50%; transform: translateY(-50%);" class="w-4 h-4 absolute left-3.5 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Ketik untuk mencari nama device atau lokasi..."
                                   style="padding-left: 40px !important;"
                                   class="form-input"
                                   oninput="clearTimeout(window.searchTimer); window.searchTimer=setTimeout(() => window.performAjaxSearch(this.form), 500)">
                            @if(!empty($search))
                                <a href="{{ route('devices.index') }}" style="top: 50%; transform: translateY(-50%); right: 14px;" class="absolute text-slate-400 hover:text-rose-500 transition p-1" title="Hapus Pencarian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </form>
                    <div class="text-xs text-slate-500 font-bold bg-slate-100 px-3 py-2 rounded-xl border border-slate-200 text-center sm:text-left whitespace-nowrap">Total: {{ $devices->total() }} Device</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th>Nama Device</th>
                            <th>Mode / Tipe</th>
                            <th>Lokasi</th>
                            <th>Token Device</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $d)
                            <tr class="table-row">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg {{ ($d->tipe_device ?? 'kiosk') === 'microcontroller' ? 'bg-violet-50 border-violet-100 text-violet-600' : 'bg-indigo-50 border-indigo-100 text-indigo-600' }} border flex items-center justify-center shrink-0">
                                            @if(($d->tipe_device ?? 'kiosk') === 'microcontroller')
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                            @else
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $d->nama_device }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if(($d->tipe_device ?? 'kiosk') === 'microcontroller')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold text-violet-700 bg-violet-50 border border-violet-200 rounded-lg">
                                            <span>Microcontroller IoT</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg">
                                            <span>Kiosk Browser</span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-xs text-slate-600 font-medium">{{ $d->lokasi ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <code class="font-mono text-[11px] bg-slate-100 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg font-semibold max-w-[180px] truncate" title="{{ $d->token_device }}">
                                            {{ substr($d->token_device, 0, 12) }}...
                                        </code>
                                        <form method="POST" action="{{ route('devices.regenerate', $d) }}" class="inline"
                                              onsubmit="confirmDelete(event, 'Yakin ingin me-reset token device {{ $d->nama_device }}? Token lama akan berhenti berfungsi.')">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition flex items-center gap-1" title="Regenerate Token">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Reset</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $d->is_active ? 'badge-green' : 'badge-gray' }}">
                                        {{ $d->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                 <td class="text-right">
                                     <div class="flex items-center justify-end gap-1.5">
                                         @if(($d->tipe_device ?? 'kiosk') === 'kiosk')
                                             <a href="{{ route('kiosk.scan') }}?token={{ $d->token_device }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition" title="Buka Halaman Kiosk Device Ini">
                                                 <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                 <span>Kiosk</span>
                                             </a>
                                             <button onclick="copyToClipboard('{{ route('kiosk.scan') }}?token={{ $d->token_device }}', this)"
                                                     class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition" title="Salin Link Kiosk Device Ini">
                                                 <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                 <span>Link</span>
                                             </button>
                                         @endif
                                         <a href="{{ route('devices.edit', $d) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 rounded-lg transition">
                                             <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                             </svg>
                                             <span>Edit</span>
                                         </a>
                                         <form method="POST" action="{{ route('devices.destroy', $d) }}" class="inline"
                                               onsubmit="confirmDelete(event, 'Yakin ingin menghapus device {{ $d->nama_device }}?')">
                                             @csrf @method('DELETE')
                                             <button type="submit"
                                                     class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-lg transition">
                                                 <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                 </svg>
                                                 <span>Hapus</span>
                                             </button>
                                         </form>
                                     </div>
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="text-sm font-medium">Belum ada device terdaftar.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($devices->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $devices->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-700">Tersalin</span>';
                setTimeout(() => { btn.innerHTML = orig; }, 1800);
            });
        }
    </script>
</x-app-layout>
