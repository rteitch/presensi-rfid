<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Import Data Siswa</h1>
                <p class="text-sm text-slate-500 mt-0.5">Upload file Excel (.xlsx/.xls/.csv) untuk import data siswa secara massal.</p>
            </div>
            <a href="{{ route('students.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl space-y-4">
        @if(session('error'))
            <div class="alert-danger">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Panduan Format -->
        <div class="page-card p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Panduan Format Import</h3>
                    <p class="text-xs text-slate-500">Pastikan file sesuai format agar data berhasil diimport.</p>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-2 font-bold text-slate-700">Kolom</th>
                            <th class="text-left py-2 font-bold text-slate-700">Keterangan</th>
                            <th class="text-left py-2 font-bold text-slate-700">Wajib?</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">nis</td>
                            <td class="py-1.5">Nomor Induk Siswa (unik)</td>
                            <td class="py-1.5"><span class="badge badge-red text-[10px]">Wajib</span></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">nama</td>
                            <td class="py-1.5">Nama lengkap siswa</td>
                            <td class="py-1.5"><span class="badge badge-red text-[10px]">Wajib</span></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">rfid_uid</td>
                            <td class="py-1.5">UID kartu RFID (opsional)</td>
                            <td class="py-1.5"><span class="badge badge-gray text-[10px]">Opsional</span></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">kelas</td>
                            <td class="py-1.5">Nama kelas (harus sudah ada di sistem)</td>
                            <td class="py-1.5"><span class="badge badge-red text-[10px]">Wajib</span></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">nama_ortu</td>
                            <td class="py-1.5">Nama orang tua / wali</td>
                            <td class="py-1.5"><span class="badge badge-gray text-[10px]">Opsional</span></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-1.5 font-mono font-bold">no_hp_ortu</td>
                            <td class="py-1.5">No HP orang tua</td>
                            <td class="py-1.5"><span class="badge badge-gray text-[10px]">Opsional</span></td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-mono font-bold">status</td>
                            <td class="py-1.5">aktif atau nonaktif (default: aktif)</td>
                            <td class="py-1.5"><span class="badge badge-gray text-[10px]">Opsional</span></td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex items-center gap-2 pt-2 border-t border-slate-200">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs text-slate-500">Jika NIS sudah ada, data akan diupdate. Jika kelas tidak ditemukan, baris tersebut dilewati.</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('students.template') }}" class="btn-secondary text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Template Excel
                </a>
            </div>
        </div>

        <!-- Form Upload -->
        <div class="page-card p-6">
            <form method="POST" action="{{ route('students.import-store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Pilih File Excel <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                           class="form-input text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('file') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('students.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
