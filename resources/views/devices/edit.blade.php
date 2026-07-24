<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Device RFID</h1>
                <p class="text-sm text-slate-500 mt-0.5">Ubah informasi device scanner RFID.</p>
            </div>
            <a href="{{ route('devices.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="page-card p-6 md:p-8">
            <form method="POST" action="{{ route('devices.update', $device) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Sisi Kiri: Identitas Device -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Informasi Device</h3>

                        <div>
                            <label class="form-label">Nama Device <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_device" value="{{ old('nama_device', $device->nama_device) }}" required class="form-input">
                            @error('nama_device') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Mode Skenario Perangkat <span class="text-rose-500">*</span></label>
                            <select name="tipe_device" class="form-input font-medium" required>
                                <option value="kiosk" {{ old('tipe_device', $device->tipe_device ?? 'kiosk') === 'kiosk' ? 'selected' : '' }}>🖥️ Mode 1: Kiosk Browser (Komputer/Laptop + Reader USB HID)</option>
                                <option value="microcontroller" {{ old('tipe_device', $device->tipe_device) === 'microcontroller' ? 'selected' : '' }}>🔌 Mode 2: Microcontroller IoT Box (ESP32 / NodeMCU + MFRC522)</option>
                            </select>
                            <p class="text-xs text-slate-400 mt-1">Pilih Mode 1 untuk Komputer Kiosk biasa, atau Mode 2 untuk box hardware IoT mandiri.</p>
                            @error('tipe_device') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Lokasi Pemasangan Device</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $device->lokasi) }}" class="form-input">
                            @error('lokasi') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label">Status Device</label>
                            <select name="is_active" class="form-input">
                                <option value="1" {{ old('is_active', $device->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !old('is_active', $device->is_active) ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Token Device -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-700 border-b border-slate-100 pb-2">Keamanan & Token API</h3>

                        <div class="p-4 bg-indigo-50/60 border border-indigo-100 rounded-xl space-y-2">
                            <label class="form-label text-indigo-900 font-bold mb-0">Token Autentikasi Device <span class="text-rose-500">*</span></label>
                            <p class="text-xs text-indigo-700">Token unik ini dikirim via header <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-indigo-200">X-Device-Token</code> oleh perangkat pemindai RFID.</p>
                            <div class="flex gap-2 pt-1">
                                <input type="text" name="token_device" value="{{ old('token_device', $device->token_device) }}" required class="form-input font-mono flex-1 text-slate-900 font-bold">
                                <button type="button" onclick="document.querySelector('[name=token_device]').value=Math.random().toString(36).substring(2)+Math.random().toString(36).substring(2)" class="btn-secondary text-xs shrink-0">Generate Baru</button>
                            </div>
                            @error('token_device') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('devices.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
