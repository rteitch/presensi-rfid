<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('teachers.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Guru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Detail informasi data pengajar & wali kelas.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="page-card p-6 flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="w-28 h-28 rounded-2xl bg-indigo-50 border-2 border-indigo-100 flex items-center justify-center text-indigo-600 font-extrabold text-3xl shrink-0 overflow-hidden shadow-sm">
                @if($teacher->foto && Storage::disk('public')->exists($teacher->foto))
                    <img src="{{ Storage::url($teacher->foto) }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($teacher->nama, 0, 2)) }}
                @endif
            </div>

            <div class="flex-1 text-center md:text-left space-y-3">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $teacher->nama }}</h2>
                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mt-0.5">{{ $teacher->mata_pelajaran ?? 'Guru Pengajar' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-slate-400 block font-semibold">NIP / Kode Guru:</span>
                        <span class="font-mono text-slate-800 font-bold">{{ $teacher->nip ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Jenis Kelamin:</span>
                        <span class="text-slate-800 font-semibold">{{ $teacher->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">No HP / WhatsApp:</span>
                        <span class="font-mono text-slate-800 font-bold">{{ $teacher->no_hp ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Akun Login System:</span>
                        <span class="text-slate-800 font-semibold">{{ $teacher->email ?? '-' }}</span>
                    </div>
                </div>

                @if($teacher->user && $teacher->user->managedClasses->count() > 0)
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Wali Kelas Binaan:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($teacher->user->managedClasses as $c)
                                <a href="{{ route('classes.show', $c) }}" class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold hover:bg-indigo-100 transition">
                                    Kelas {{ $c->nama_kelas }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Profil Guru</span>
            </a>
        </div>
    </div>
</x-app-layout>
