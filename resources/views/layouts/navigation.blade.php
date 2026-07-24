<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo + Nama Sekolah -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        @if($schoolSettings['logo_url'])
                            <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="h-9 w-auto object-contain">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                        <div class="hidden sm:block leading-tight">
                            <div class="font-bold text-gray-900 text-sm">{{ $schoolSettings['school_name'] }}</div>
                            @if($schoolSettings['school_tagline'])
                                <div class="text-gray-400 text-xs">{{ $schoolSettings['school_tagline'] }}</div>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    @role('admin')
                    <!-- Dropdown: Data Master -->
                    <div class="relative" x-data="{ openData: false }" @mouseleave="openData = false">
                        <button @mouseenter="openData = true" @click="openData = !openData"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition
                                       {{ request()->routeIs('students.*') || request()->routeIs('classes.*') || request()->routeIs('teachers.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Data Master
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openData" x-transition
                             class="absolute left-0 top-full mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('students.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('students.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                👨‍🎓 Data Siswa
                            </a>
                            <a href="{{ route('teachers.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('teachers.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                👨‍🏫 Data Guru
                            </a>
                            <a href="{{ route('classes.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('classes.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                🏫 Data Kelas
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown: Pengaturan -->
                    <div class="relative" x-data="{ openSet: false }" @mouseleave="openSet = false">
                        <button @mouseenter="openSet = true" @click="openSet = !openSet"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition
                                       {{ request()->routeIs('settings.*') || request()->routeIs('devices.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            ⚙️ Pengaturan
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openSet" x-transition
                             class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('settings.index') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                🕐 Pengaturan Presensi
                            </a>
                            <a href="{{ route('settings.school') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('settings.school') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                🏫 Konfigurasi Sekolah
                            </a>
                            <a href="{{ route('devices.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('devices.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                📡 Device RFID
                            </a>
                        </div>
                    </div>
                    @endrole

                    <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">
                        Presensi
                    </x-nav-link>

                    <!-- Dropdown: Laporan -->
                    <div class="relative" x-data="{ openRep: false }" @mouseleave="openRep = false">
                        <button @mouseenter="openRep = true" @click="openRep = !openRep"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md transition
                                       {{ request()->routeIs('reports.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            📊 Laporan
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openRep" x-transition
                             class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('reports.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('reports.index') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                📋 Laporan Harian
                            </a>
                            <a href="{{ route('reports.rekap') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('reports.rekap') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                                📊 Rekap + WA Ortu
                            </a>
                            <a href="{{ route('reports.leaderboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 {{ request()->routeIs('reports.leaderboard') ? 'bg-amber-50 text-amber-700' : '' }}">
                                🏆 Leaderboard
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('kiosk.scan') }}" target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-md transition">
                        🖥️ Kiosk
                    </a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition">
                            <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            @role('admin')
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">👨‍🎓 Data Siswa</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')">👨‍🏫 Data Guru</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')">🏫 Data Kelas</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.index')">🕐 Pengaturan Presensi</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('settings.school')" :active="request()->routeIs('settings.school')">🏫 Konfigurasi Sekolah</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('devices.index')" :active="request()->routeIs('devices.*')">📡 Device RFID</x-responsive-nav-link>
            @endrole
            <x-responsive-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">Presensi Harian</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')">📋 Laporan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.rekap')" :active="request()->routeIs('reports.rekap')">📊 Rekap + WA</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.leaderboard')" :active="request()->routeIs('reports.leaderboard')">🏆 Leaderboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kiosk.scan')" target="_blank">🖥️ Mode Kiosk</x-responsive-nav-link>
        </div>

        <!-- Responsive User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profil Saya</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
