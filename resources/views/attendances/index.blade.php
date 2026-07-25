<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Presensi Harian</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Monitoring kehadiran siswa — <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span></p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-5">
        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Tabel Presensi -->
            <div class="lg:col-span-2 space-y-4">
                <div class="page-card">
                    <!-- Filter Bar -->
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" id="attendance-filter-form">
                            <div>
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input" onchange="this.form.submit()">
                            </div>
                            <div>
                                <label class="form-label">Kelas</label>
                                <select name="class_id" class="form-input" onchange="this.form.submit()">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Cari Siswa</label>
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama atau NIS..." class="form-input" oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)">
                            </div>
                            <div class="flex items-end">
                                @if($search || $classId)
                                    <a href="{{ route('attendances.index', ['tanggal' => $tanggal]) }}" class="btn-secondary w-full justify-center text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Reset Filter
                                    </a>
                                @else
                                    <button type="submit" class="btn-primary w-full justify-center text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                                        Filter
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="table-head">
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $att)
                                    <tr class="table-row">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                @if($att->student)
                                                    <img src="{{ $att->student->foto_url }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shrink-0">
                                                @endif
                                                <span class="font-bold text-slate-900">{{ $att->student->nama ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-indigo">{{ $att->student->schoolClass->nama_kelas ?? '-' }}</span>
                                        </td>
                                        <td class="font-mono text-xs font-bold text-slate-800">{{ $att->jam_masuk ?? '—' }}</td>
                                        <td class="font-mono text-xs font-bold text-slate-800">{{ $att->jam_pulang ?? '—' }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($att->status) {
                                                    'hadir' => 'badge-green',
                                                    'terlambat' => 'badge-amber',
                                                    'izin' => 'badge-blue',
                                                    'sakit' => 'badge-indigo',
                                                    'alpha' => 'badge-red',
                                                    default => 'badge-gray',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst($att->status) }}</span>
                                        </td>
                                        <td class="text-xs text-slate-500 font-medium">{{ $att->keterangan ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                Belum ada data presensi pada tanggal ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($attendances->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $attendances->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Input Manual -->
            <div>
                <div class="page-card">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm">Input Presensi Manual & Izin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Untuk siswa Izin, Sakit, Dispensasi, atau Alpha. Bisa pilih lebih dari 1 siswa sekaligus.</p>
                    </div>
                    <div class="p-5">
                        <form method="POST" action="{{ route('attendances.manual') }}" class="space-y-4">
                            @csrf

                            <!-- Select2 Multi-select Siswa -->
                            <div>
                                <label class="form-label font-bold text-slate-800">
                                    Pilih Siswa
                                    <span class="text-rose-500">*</span>
                                    <span class="text-xs font-normal text-indigo-600 ml-1">(bisa pilih lebih dari 1)</span>
                                </label>
                                <select name="student_id[]" id="select-students" multiple required class="w-full" style="width:100%">
                                    @foreach($classes as $c)
                                        @php $classStudents = $students->where('class_id', $c->id); @endphp
                                        @if($classStudents->isNotEmpty())
                                            <optgroup label="{{ $c->nama_kelas }}">
                                                @foreach($classStudents as $st)
                                                    <option value="{{ $st->id }}">{{ $st->nama }} — {{ $st->nis ?? 'NIS -' }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                    @php $noClassStudents = $students->whereNull('class_id'); @endphp
                                    @if($noClassStudents->isNotEmpty())
                                        <optgroup label="— Tanpa Kelas —">
                                            @foreach($noClassStudents as $st)
                                                <option value="{{ $st->id }}">{{ $st->nama }} — {{ $st->nis ?? 'NIS -' }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                <p class="text-[11px] text-slate-400 mt-1.5">Ketik nama/NIS untuk mencari. Klik+Ctrl untuk pilih banyak, atau gunakan kotak pencarian Select2.</p>
                            </div>

                            <div>
                                <label class="form-label font-bold text-slate-800">Tanggal <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" required class="form-input text-sm font-semibold">
                            </div>
                            <div>
                                <label class="form-label font-bold text-slate-800">Status Presensi <span class="text-rose-500">*</span></label>
                                <select name="status" required class="form-input text-sm font-semibold">
                                    <option value="izin">Izin / Pulang Cepat / Dispensasi</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha (Tanpa Keterangan)</option>
                                    <option value="hadir">Hadir (Manual)</option>
                                    <option value="terlambat">Terlambat (Manual)</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label font-bold text-slate-800">Keterangan / Alasan</label>
                                <textarea name="keterangan" rows="3" placeholder="Contoh: Dispensasi lomba matematika tingkat kota, atau Izin pulang jam 10:00..." class="form-input text-sm resize-none"></textarea>
                                <p class="text-[11px] text-slate-400 mt-1">Catatan: Data presensi siswa terpilih di tanggal tersebut akan diperbarui.</p>
                            </div>
                            <button type="submit" class="btn-primary w-full justify-center shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Presensi Manual</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Select2: Load jQuery first (required), then Select2 --}}
    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        /* Select2 theming agar menyesuaikan desain aplikasi */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.3rem 0.5rem;
            min-height: 44px;
            background: #fff;
            font-size: 0.875rem;
            cursor: text;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgb(99 102 241 / 0.15);
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 12px 32px rgb(0 0 0 / 0.12);
            font-size: 0.875rem;
            overflow: hidden;
        }
        .select2-search--dropdown {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0;
            border-radius: 0.4rem;
            padding: 0.45rem 0.65rem;
            font-size: 0.875rem;
            width: 100%;
            outline: none;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgb(99 102 241 / 0.15);
        }
        .select2-results__option {
            padding: 0.5rem 0.75rem;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #6366f1;
            color: #fff;
        }
        .select2-container--default .select2-results__option--selected {
            background-color: #f0f0ff;
            color: #4338ca;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #6366f1;
            border: none;
            color: #fff;
            border-radius: 0.375rem;
            padding: 2px 10px 2px 6px;
            font-size: 0.75rem;
            line-height: 1.6;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.75);
            border: none;
            background: none;
            margin-right: 2px;
            font-size: 1rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
        }
        .select2-results__group {
            color: #94a3b8;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            padding: 8px 12px 4px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: #94a3b8;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#select-students').select2({
                placeholder: 'Cari nama atau NIS siswa...',
                allowClear: true,
                width: '100%',
                closeOnSelect: false,
                language: {
                    noResults: function () { return 'Siswa tidak ditemukan'; },
                    searching:  function () { return 'Mencari siswa...'; },
                    removeAllItems: function() { return 'Hapus semua'; },
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
