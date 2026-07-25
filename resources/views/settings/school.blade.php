<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-50 border border-indigo-100 rounded-xl shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Konfigurasi Aplikasi & Sekolah</h1>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Kelola identitas aplikasi, nama sekolah, wallpaper kiosk, hari sekolah efektif, dan rate limit.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ activeTab: window.location.hash ? window.location.hash.replace('#', '') : 'identitas' }">

        @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tab Switcher Navigation -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <button type="button" @click="activeTab = 'identitas'; window.location.hash = 'identitas'"
                    :class="activeTab === 'identitas' ? 'bg-indigo-600 text-white shadow-sm font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Identitas Sekolah & Logo</span>
            </button>

            <button type="button" @click="activeTab = 'kiosk'; window.location.hash = 'kiosk'"
                    :class="activeTab === 'kiosk' ? 'bg-indigo-600 text-white shadow-sm font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Tampilan Kiosk Scanner</span>
            </button>

            <button type="button" @click="activeTab = 'hari-efektif'; window.location.hash = 'hari-efektif'"
                    :class="activeTab === 'hari-efektif' ? 'bg-indigo-600 text-white shadow-sm font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Hari Sekolah Efektif</span>
            </button>

            <button type="button" @click="activeTab = 'security'; window.location.hash = 'security'"
                    :class="activeTab === 'security' ? 'bg-indigo-600 text-white shadow-sm font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Security & Rate Limit</span>
            </button>
        </div>

        <form method="POST" action="{{ route('settings.school.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Tab 1: Identitas Aplikasi & Sekolah -->
            <div x-show="activeTab === 'identitas'" x-transition class="page-card">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Identitas Utama Sekolah & Aplikasi</h3>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Nama Aplikasi -->
                    <div class="md:col-span-2">
                        <label for="app_name" class="form-label">Nama Aplikasi System <span class="text-rose-500">*</span></label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $s['app_name'] ?? 'PRESENSI RTH NEXUS') }}"
                               class="form-input font-extrabold tracking-wide text-indigo-700"
                               placeholder="Contoh: PRESENSI RTH NEXUS">
                        <p class="text-xs text-slate-400 mt-1">Nama sistem utama yang akan tampil di topbar, title bar, dan halaman kiosk.</p>
                        @error('app_name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Logo Sekolah / Aplikasi -->
                    <div class="md:col-span-2">
                        <label class="form-label">Logo Aplikasi / Sekolah</label>
                        <div class="flex items-center gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <div id="logo-preview-wrapper" class="w-20 h-20 rounded-xl border border-slate-300 flex items-center justify-center bg-white overflow-hidden shrink-0 shadow-sm">
                                @if($schoolSettings['logo_url'])
                                    <img id="logo-preview" src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="object-contain p-1.5" style="max-height:72px; max-width:72px;">
                                @else
                                    <div id="logo-placeholder" class="text-center p-2">
                                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[10px] text-slate-400">No logo</span>
                                    </div>
                                    <img id="logo-preview" class="object-contain p-1.5 hidden" style="max-height:72px; max-width:72px;">
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <label for="school_logo" class="btn-secondary text-xs cursor-pointer inline-flex">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Pilih File Logo Baru</span>
                                </label>
                                <input type="file" name="school_logo" id="school_logo" accept="image/*" class="sr-only" onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">
                                <p class="text-xs text-slate-400">PNG, JPG, SVG, WebP. Maks: 2MB (disarankan latar transparan).</p>
                                @error('school_logo') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Aplikasi -->
                    <div class="md:col-span-2">
                        <label class="form-label">Favicon Tab Browser (.ico / .png)</label>
                        <div class="flex items-center gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            @php
                                $faviconPath = \App\Models\SchoolSetting::get('school_favicon');
                                $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : null;
                            @endphp
                            <div id="favicon-preview-wrapper" class="w-12 h-12 rounded-xl border border-slate-300 flex items-center justify-center bg-white overflow-hidden shrink-0 shadow-sm">
                                @if($faviconUrl)
                                    <img id="favicon-preview" src="{{ $faviconUrl }}" alt="Favicon" class="object-contain p-1" style="max-height:32px; max-width:32px;">
                                @else
                                    <div id="favicon-placeholder" class="text-center p-1">
                                        <svg class="w-5 h-5 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                    <img id="favicon-preview" class="object-contain p-1 hidden" style="max-height:32px; max-width:32px;">
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <label for="school_favicon" class="btn-secondary text-xs cursor-pointer inline-flex">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Pilih File Favicon Baru</span>
                                </label>
                                <input type="file" name="school_favicon" id="school_favicon" accept="image/*,.ico" class="sr-only" onchange="previewImage(this, 'favicon-preview', 'favicon-placeholder')">
                                <p class="text-xs text-slate-400">ICO, PNG, SVG. Ukuran ideal: 32x32px atau 64x64px. Maks: 1MB.</p>
                                @error('school_favicon') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Sekolah -->
                    <div class="md:col-span-2">
                        <label for="school_name" class="form-label">Nama Sekolah / Instansi <span class="text-rose-500">*</span></label>
                        <input type="text" name="school_name" id="school_name" value="{{ old('school_name', $s['school_name'] ?? '') }}"
                               class="form-input font-semibold"
                               placeholder="Contoh: SMPN 1 Contoh">
                        @error('school_name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tagline -->
                    <div class="md:col-span-2">
                        <label for="school_tagline" class="form-label">Tagline / Motto Sekolah</label>
                        <input type="text" name="school_tagline" id="school_tagline" value="{{ old('school_tagline', $s['school_tagline'] ?? '') }}"
                               class="form-input"
                               placeholder="Contoh: Disiplin, Cerdas, Berkarakter">
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="school_address" class="form-label">Alamat Lengkap Sekolah</label>
                        <input type="text" name="school_address" id="school_address" value="{{ old('school_address', $s['school_address'] ?? '') }}"
                               class="form-input"
                               placeholder="Contoh: Jl. Pendidikan No. 1, Kota Contoh">
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="school_phone" class="form-label">Nomor Telepon Kontak</label>
                        <input type="text" name="school_phone" id="school_phone" value="{{ old('school_phone', $s['school_phone'] ?? '') }}"
                               class="form-input font-mono"
                               placeholder="Contoh: (021) 123-4567">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="school_email" class="form-label">Email Resmi Sekolah</label>
                        <input type="email" name="school_email" id="school_email" value="{{ old('school_email', $s['school_email'] ?? '') }}"
                               class="form-input"
                               placeholder="Contoh: info@sekolah.sch.id">
                    </div>

                    <!-- Footer Text -->
                    <div class="md:col-span-2">
                        <label for="footer_text" class="form-label">Teks Hak Cipta / Footer</label>
                        <input type="text" name="footer_text" id="footer_text" value="{{ old('footer_text', $s['footer_text'] ?? '') }}"
                               class="form-input"
                               placeholder="Contoh: PRESENSI RTH NEXUS — Hak Cipta © 2026. All rights reserved.">
                    </div>
                </div>
            </div>

            <!-- Tab 2: Tampilan Kiosk Scanner -->
            <div x-show="activeTab === 'kiosk'" x-transition class="page-card">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Tampilan Layar Kiosk Scanner</h3>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Judul & Subtitle Kiosk -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="kiosk_title" class="form-label">Judul Utama Kiosk</label>
                            <input type="text" name="kiosk_title" id="kiosk_title" value="{{ old('kiosk_title', $s['kiosk_title'] ?? '') }}"
                                   class="form-input font-bold"
                                   placeholder="Contoh: PRESENSI RTH NEXUS">
                        </div>
                        <div>
                            <label for="kiosk_subtitle" class="form-label">Subtitle / Instruksi Pemindaian</label>
                            <input type="text" name="kiosk_subtitle" id="kiosk_subtitle" value="{{ old('kiosk_subtitle', $s['kiosk_subtitle'] ?? '') }}"
                                   class="form-input"
                                   placeholder="Contoh: Tempelkan Kartu RFID pada Reader">
                        </div>
                    </div>

                    <!-- Tipe Background -->
                    <div>
                        <label class="form-label">Tipe Background Layar Kiosk</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="bg-type-group">
                            @foreach(['gradient' => ['label' => 'Gradien Dark', 'desc' => 'Gelap Indigo Elegan'], 'color' => ['label' => 'Warna Solid', 'desc' => 'Warna Kustom'], 'image' => ['label' => 'Gambar / Wallpaper', 'desc' => 'Upload Foto Background']] as $val => $opt)
                            <label class="relative cursor-pointer" for="bg_type_{{ $val }}">
                                <input type="radio" name="kiosk_bg_type" id="bg_type_{{ $val }}" value="{{ $val }}"
                                       class="sr-only peer"
                                       {{ old('kiosk_bg_type', $s['kiosk_bg_type'] ?? 'gradient') === $val ? 'checked' : '' }}
                                       onchange="handleBgTypeChange()">
                                <div class="flex flex-col items-center gap-1 p-4 rounded-xl border-2 border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 hover:border-slate-300 transition text-center shadow-sm">
                                    <span class="text-sm font-bold text-slate-800">{{ $opt['label'] }}</span>
                                    <span class="text-xs text-slate-400">{{ $opt['desc'] }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Picker -->
                    <div id="section-color" class="{{ old('kiosk_bg_type', $s['kiosk_bg_type'] ?? 'gradient') !== 'color' ? 'hidden' : '' }}">
                        <label for="kiosk_bg_color" class="form-label">Pilih Warna Background Solid</label>
                        <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <input type="color" name="kiosk_bg_color" id="kiosk_bg_color"
                                   value="{{ old('kiosk_bg_color', $s['kiosk_bg_color'] ?? '#0f172a') }}"
                                   class="w-14 h-10 rounded-lg border border-slate-300 cursor-pointer p-1"
                                   oninput="updateColorPreview(this)">
                            <div class="flex-1 h-10 rounded-lg border border-slate-200 transition-all shadow-inner" id="color-swatch"
                                 style="background-color: {{ old('kiosk_bg_color', $s['kiosk_bg_color'] ?? '#0f172a') }}">
                            </div>
                            <span id="color-hex" class="text-xs font-mono text-slate-700 font-bold w-16 text-center">{{ old('kiosk_bg_color', $s['kiosk_bg_color'] ?? '#0f172a') }}</span>
                        </div>
                    </div>

                    <!-- Wallpaper Upload -->
                    <div id="section-image" class="{{ old('kiosk_bg_type', $s['kiosk_bg_type'] ?? 'gradient') !== 'image' ? 'hidden' : '' }}">
                        <label class="form-label">Upload Gambar Wallpaper Layar Kiosk</label>
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-32 h-20 rounded-xl border border-slate-300 bg-white overflow-hidden shrink-0 flex items-center justify-center shadow-sm">
                                @if($schoolSettings['kiosk_bg_image_url'])
                                    <img id="kiosk-preview" src="{{ $schoolSettings['kiosk_bg_image_url'] }}" class="w-full h-full object-cover">
                                @else
                                    <img id="kiosk-preview" class="w-full h-full object-cover hidden">
                                    <div id="kiosk-placeholder" class="text-center p-2">
                                        <span class="text-xs text-slate-400 font-medium">Belum ada</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <label for="kiosk_bg_image" class="btn-secondary text-xs cursor-pointer inline-flex">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span>Pilih Wallpaper (1920x1080)</span>
                                </label>
                                <input type="file" name="kiosk_bg_image" id="kiosk_bg_image" accept="image/*" class="sr-only" onchange="previewImage(this, 'kiosk-preview', 'kiosk-placeholder')">
                                <p class="text-xs text-slate-400">JPG, PNG, WebP. Maksimal: 4MB.</p>
                                @error('kiosk_bg_image') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Kiosk Box -->
                    <div class="pt-2">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Preview Tampilan Kiosk Live</p>
                        <div id="kiosk-live-preview" class="relative w-full h-48 rounded-2xl overflow-hidden flex flex-col items-center justify-center gap-2 transition-all duration-300 shadow-inner border border-slate-300"
                             style="{{ $schoolSettings['kiosk_bg_style'] }}">
                            <div class="relative z-10 text-center px-4">
                                @if($schoolSettings['logo_url'])
                                    <img id="preview-logo" src="{{ $schoolSettings['logo_url'] }}" class="mx-auto mb-2 object-contain" style="max-height:38px; max-width:120px;">
                                @else
                                    <img id="preview-logo" class="mx-auto mb-2 object-contain hidden" style="max-height:38px; max-width:120px;">
                                @endif
                                <p id="preview-title" class="text-white font-extrabold text-lg drop-shadow">{{ $schoolSettings['kiosk_title'] ?? 'PRESENSI RTH NEXUS' }}</p>
                                <p id="preview-subtitle" class="text-white/80 text-xs">{{ $schoolSettings['kiosk_subtitle'] ?? 'Tempelkan Kartu RFID' }}</p>
                                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 border border-white/30 backdrop-blur-md">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-white text-xs font-semibold">Ready to Scan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Hari Sekolah Efektif -->
            <div x-show="activeTab === 'hari-efektif'" x-transition class="page-card">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Konfigurasi Hari Sekolah Efektif</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label text-sm font-bold text-slate-900 mb-1">Centang Hari Sekolah Efektif <span class="text-rose-500">*</span></label>
                        @php
                            $rawSetting = \App\Models\SchoolSetting::get('hari_efektif');
                            $selectedDays = old('hari_efektif', $rawSetting ? json_decode($rawSetting, true) : ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
                            $allDays = [
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu',
                            ];
                        @endphp
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3 mt-3">
                            @foreach($allDays as $enDay => $idDay)
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-indigo-50/50 hover:border-indigo-300 cursor-pointer transition shadow-sm">
                                    <input type="checkbox" name="hari_efektif[]" value="{{ $enDay }}"
                                           {{ in_array($enDay, (array)$selectedDays) ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                    <span class="text-xs font-bold text-slate-800">{{ $idDay }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1">
                            <span class="font-bold text-slate-900 block">💡 Pengaruh Sistem Hari Sekolah Efektif:</span>
                            <p>• <strong>Kiosk RFID Scanner</strong>: Pemindaian kartu pada hari non-efektif (yang tidak dicentang) akan otomatis ditolak dengan pesan <em>"Hari ini adalah hari libur rutin sekolah."</em></p>
                            <p>• <strong>Scheduler Auto-Alpha</strong>: Perintah otomatis <code>php artisan attendance:auto-alpha</code> akan membatalkan penandaan Alpha pada hari non-efektif.</p>
                            <p>• <strong>Universal</strong>: Sangat cocok untuk **Sekolah Umum 5 Hari** (Senin–Jumat), **Sekolah 6 Hari** (Senin–Sabtu), maupun **Pesantren / Sekolah Islam** (Sabtu–Kamis, Jumat Libur).</p>
                        </div>
                        @error('hari_efektif') <p class="text-xs text-rose-500 mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Tab 4: Security & Rate Limit -->
            <div x-show="activeTab === 'security'" x-transition class="page-card">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Performa & Security Rate Limit API</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="max-w-xl space-y-2">
                        <label for="rate_limit_api" class="form-label font-bold text-slate-800">Batas Rate Limit API Eksternal (Request / Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" min="10" max="1000" name="rate_limit_api" id="rate_limit_api"
                               value="{{ old('rate_limit_api', \App\Models\SchoolSetting::get('rate_limit_api', 60)) }}"
                               class="form-input text-sm font-semibold max-w-xs" required>
                        <p class="text-xs text-slate-500 leading-relaxed mt-1">
                            Batas maksimal jumlah panggilan request per menit untuk integrasi API pihak ketiga (misal integrasi rekap SIM Sekolah). 
                            <br><span class="text-emerald-600 font-semibold">• Pemindaian Kiosk RFID Sekolah dijamin 100% bebas hambatan (unlimited) tanpa terpengaruh batas ini.</span>
                        </p>
                        @error('rate_limit_api') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Global Submit Buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Seluruh Konfigurasi</span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = placeholderId ? document.getElementById(placeholderId) : null;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');

                    if (input.id === 'school_logo') {
                        const previewLogoEl = document.getElementById('preview-logo');
                        previewLogoEl.src = e.target.result;
                        previewLogoEl.classList.remove('hidden');
                    }

                    if (input.id === 'kiosk_bg_image') {
                        const lp = document.getElementById('kiosk-live-preview');
                        lp.style.backgroundImage = `url(${e.target.result})`;
                        lp.style.backgroundSize = 'cover';
                        lp.style.backgroundPosition = 'center';
                        lp.style.background = '';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleBgTypeChange() {
            const type = document.querySelector('input[name="kiosk_bg_type"]:checked')?.value;
            const secColor = document.getElementById('section-color');
            const secImage = document.getElementById('section-image');
            const lp = document.getElementById('kiosk-live-preview');

            secColor.classList.add('hidden');
            secImage.classList.add('hidden');

            if (type === 'color') {
                secColor.classList.remove('hidden');
                const color = document.getElementById('kiosk_bg_color').value;
                lp.removeAttribute('style');
                lp.style.backgroundColor = color;
            } else if (type === 'image') {
                secImage.classList.remove('hidden');
                const kioskPreview = document.getElementById('kiosk-preview');
                if (kioskPreview && !kioskPreview.classList.contains('hidden') && kioskPreview.src) {
                    lp.style.backgroundImage = `url(${kioskPreview.src})`;
                    lp.style.backgroundSize = 'cover';
                } else {
                    lp.removeAttribute('style');
                    lp.style.background = 'linear-gradient(135deg, #1e293b, #334155)';
                }
            } else {
                lp.removeAttribute('style');
                lp.style.background = 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%)';
            }
        }

        function updateColorPreview(input) {
            const swatch = document.getElementById('color-swatch');
            const hexLabel = document.getElementById('color-hex');
            const lp = document.getElementById('kiosk-live-preview');
            swatch.style.backgroundColor = input.value;
            hexLabel.textContent = input.value;
            lp.removeAttribute('style');
            lp.style.backgroundColor = input.value;
        }

        document.getElementById('kiosk_title')?.addEventListener('input', e => {
            document.getElementById('preview-title').textContent = e.target.value || 'PRESENSI RTH NEXUS';
        });
        document.getElementById('kiosk_subtitle')?.addEventListener('input', e => {
            document.getElementById('preview-subtitle').textContent = e.target.value || 'Tempelkan Kartu RFID';
        });
    </script>
    @endpush
</x-app-layout>
