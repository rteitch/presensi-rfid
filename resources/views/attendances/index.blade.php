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
                        <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input" onchange="window.performAjaxSearch(this.form)">
                            </div>
                            <div>
                                <label class="form-label">Kelas</label>
                                <select name="class_id" class="form-input" onchange="window.performAjaxSearch(this.form)">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn-primary w-full justify-center" id="filter-btn">
                                    Filter
                                </button>
                                <script>document.getElementById('filter-btn').style.display = 'none';</script>
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
                <div class="page-card overflow-visible" x-data="{
                    selectedClass: '',
                    searchQuery: '',
                    selectedStudent: '',
                    students: @js($students->map(function($st) {
                        return [
                            'id' => $st->id,
                            'nama' => $st->nama,
                            'class_id' => $st->class_id,
                            'class_name' => $st->schoolClass->nama_kelas ?? '-'
                        ];
                    })),
                    get filteredStudents() {
                        return this.students.filter(st => {
                            const matchesClass = this.selectedClass === '' || st.class_id.toString() === this.selectedClass;
                            const matchesSearch = st.nama.toLowerCase().includes(this.searchQuery.toLowerCase());
                            return matchesClass && matchesSearch;
                        });
                    }
                }">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm">Input Presensi Manual & Izin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Untuk siswa Izin, Sakit, atau Alpha, serta Izin Pulang Cepat di tengah hari tanpa scan RFID.</p>
                    </div>
                    <div class="p-5">
                        <form method="POST" action="{{ route('attendances.manual') }}" class="space-y-4">
                            @csrf
                            <!-- Filter Kelas untuk Siswa -->
                            <div>
                                <label class="form-label text-xs">Filter Kelas</label>
                                <select x-model="selectedClass" @change="selectedStudent = ''" class="form-input text-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cari Nama Siswa -->
                            <div>
                                <label class="form-label text-xs">Cari Nama Siswa</label>
                                <input type="text" x-model="searchQuery" @input="selectedStudent = ''" placeholder="Ketik nama siswa..." class="form-input text-sm">
                            </div>

                            <!-- Pilih Siswa dari Hasil Filter -->
                            <div>
                                <label class="form-label font-bold text-slate-800">Pilih Siswa <span class="text-rose-500">*</span></label>
                                <select name="student_id" x-model="selectedStudent" required class="form-input text-sm font-semibold">
                                    <option value="">— Pilih Siswa ( <span x-text="filteredStudents.length"></span> siswa ) —</option>
                                    <template x-for="st in filteredStudents" :key="st.id">
                                        <option :value="st.id" x-text="st.nama + ' (' + st.class_name + ')'"></option>
                                    </template>
                                </select>
                                <p x-show="filteredStudents.length === 0" class="text-xs text-rose-500 mt-1 font-medium">Tidak ada siswa ditemukan dari filter tersebut.</p>
                            </div>

                            <div>
                                <label class="form-label font-bold text-slate-800">Tanggal <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" required class="form-input text-sm font-semibold">
                            </div>
                            <div>
                                <label class="form-label font-bold text-slate-800">Status Presensi <span class="text-rose-500">*</span></label>
                                <select name="status" required class="form-input text-sm font-semibold">
                                    <option value="izin">Izin / Pulang Cepat</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha (Tanpa Keterangan)</option>
                                    <option value="hadir">Hadir (Manual)</option>
                                    <option value="terlambat">Terlambat (Manual)</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label font-bold text-slate-800">Keterangan / Alasan</label>
                                <textarea name="keterangan" rows="3" placeholder="Contoh: Izin keluar jam 10:00 karena sakit, atau ada keperluan keluarga..." class="form-input text-sm resize-none"></textarea>
                                <p class="text-[11px] text-slate-400 mt-1">Catatan: Mengisi presensi manual akan menimpa/memperbarui data presensi siswa hari tersebut.</p>
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
</x-app-layout>
