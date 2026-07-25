<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $schoolSettings['app_name'] }} — {{ $schoolSettings['school_name'] }}</title>

    @php
        $faviconUrl = \App\Models\SchoolSetting::get('school_favicon')
            ? asset('storage/' . \App\Models\SchoolSetting::get('school_favicon'))
            : ($schoolSettings['logo_url'] ?? null);
    @endphp
    @if($faviconUrl)
        <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
        <link rel="shortcut icon" href="{{ $faviconUrl }}">
    @endif

    <!-- Fonts & Assets (bundled locally via Vite - Offline Ready) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Vendor Libraries: served locally from public/vendor/ - 100% Offline Ready -->
    <link rel="stylesheet" href="{{ asset('vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2.min.css') }}">

    <style>
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        body {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        /* Sidebar Base */
        .sidebar {
            width: 270px;
            min-height: 100vh;
            background: #101827;
            border-right: 1px solid #1f2937;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 12px rgba(0,0,0,0.1);
        }

        /* Desktop Collapsed Sidebar */
        @media (min-width: 769px) {
            .sidebar.collapsed {
                width: 72px;
            }
            .sidebar.collapsed .brand-text,
            .sidebar.collapsed .nav-text,
            .sidebar.collapsed .nav-group-label,
            .sidebar.collapsed .user-info,
            .sidebar.collapsed .chevron {
                display: none !important;
            }
            .sidebar.collapsed .nav-item {
                justify-content: center;
                padding: 12px;
            }
            .sidebar.collapsed .nav-sub-item {
                padding: 10px;
                justify-content: center;
            }
            .sidebar.collapsed .brand-container {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            .sidebar.collapsed .brand-container a {
                justify-content: center;
                width: auto;
            }
            .sidebar.collapsed .user-card {
                justify-content: center;
                padding: 8px;
            }
            .main-content.collapsed {
                margin-left: 72px;
            }
        }

        /* Mobile Drawer Sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            z-index: 40;
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        .main-content {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #94a3b8;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.18s ease;
            margin: 3px 0;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .nav-item:hover {
            background: #1f2937;
            color: #ffffff;
        }
        .nav-item.active {
            background: #5b4cf5;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(91, 76, 245, 0.3);
        }

        .nav-group-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #4b5563;
            padding: 20px 14px 6px;
        }

        .nav-submenu {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-submenu.open { max-height: 240px; }
        .nav-sub-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8.5px 14px 8.5px 40px;
            border-radius: 8px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 400;
            text-decoration: none;
            transition: all 0.15s ease;
            margin: 1px 0;
        }
        .nav-sub-item:hover { background: #1f2937; color: #fff; }
        .nav-sub-item.active { color: #a5b4fc; font-weight: 600; background: rgba(91, 76, 245, 0.15); }
        .chevron { transition: transform 0.25s ease; }
        .chevron.open { transform: rotate(180deg); }

        /* Professional Modern Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .page-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .table-head th {
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-row td {
            padding: 13px 18px;
            font-size: 13.5px;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-row:last-child td { border-bottom: none; }
        .table-row:nth-child(even) td { background: #fafafa; }
        .table-row:hover td { background: #f1f5f9 !important; }

        /* Topbar */
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 600; padding: 4px 11px; border-radius: 999px; }
        .badge-green { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-amber { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-red { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-indigo { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .badge-gray { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Buttons */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9.5px 18px; border-radius: 10px;
            background: #5b4cf5;
            color: #ffffff; font-size: 13.5px; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none;
            transition: all 0.18s ease;
            box-shadow: 0 2px 6px rgba(91, 76, 245, 0.25);
        }
        .btn-primary:hover { background: #4f46e5; box-shadow: 0 4px 10px rgba(91, 76, 245, 0.35); }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9.5px 16px; border-radius: 10px;
            background: white; color: #374151; font-size: 13.5px; font-weight: 600;
            border: 1px solid #d1d5db; cursor: pointer; text-decoration: none;
            transition: all 0.18s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .btn-secondary:hover { background: #f9fafb; border-color: #9ca3af; color: #111827; }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9.5px 16px; border-radius: 10px;
            background: #fef2f2; color: #dc2626; font-size: 13.5px; font-weight: 600;
            border: 1px solid #fecaca; cursor: pointer; text-decoration: none;
            transition: all 0.18s ease;
        }
        .btn-danger:hover { background: #fee2e2; border-color: #fca5a5; }

        /* Form Controls */
        .form-input {
            width: 100%; border-radius: 8px; border: 1px solid #d1d5db;
            padding: 9px 14px; font-size: 13.5px; color: #111827;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: white;
            font-family: inherit;
        }
        .form-input:focus { outline: none; border-color: #5b4cf5; box-shadow: 0 0 0 3px rgba(91,76,245,0.15); }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        /* Mobile Breakpoint */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 270px !important; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) both; }

        /* Notifications */
        .alert-success {
            display: flex; align-items: center; gap: 10px;
            background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #22c55e;
            color: #15803d; border-radius: 14px; padding: 14px 20px; font-size: 13.5px; font-weight: 500;
        }
    </style>
</head>
<body class="bg-slate-100 antialiased" style="font-family: 'Inter', sans-serif;">

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- ====== SIDEBAR ====== -->
    <aside id="sidebar" class="sidebar overflow-y-auto">

        <!-- Brand / App & School Name -->
        <div class="px-5 py-6 border-b border-slate-800/80 brand-container flex items-center justify-between mb-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 overflow-hidden w-full">
                @if($schoolSettings['logo_url'])
                    <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700/60 p-1 flex items-center justify-center shrink-0 shadow-inner">
                        <img src="{{ $schoolSettings['logo_url'] }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                @endif
                <div class="brand-text min-w-0 flex-1">
                    <div class="text-white font-extrabold text-xs tracking-wider leading-tight truncate uppercase text-indigo-300">{{ $schoolSettings['app_name'] }}</div>
                    <div class="text-slate-400 text-[11px] truncate mt-1 font-medium leading-tight">{{ $schoolSettings['school_name'] }}</div>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3.5 py-3 space-y-1">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               title="Dashboard"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- Presensi Harian -->
            <a href="{{ route('attendances.index') }}"
               title="Presensi Harian"
               class="nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-text">Presensi Harian</span>
            </a>

            <!-- Laporan Group -->
            <div class="nav-group-label">Laporan & Rekap</div>

            <button onclick="toggleSubmenu('menu-laporan', 'chev-laporan')"
                    title="Laporan"
                    class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-text flex-1 text-left">Laporan</span>
                <svg id="chev-laporan" class="chevron shrink-0 {{ request()->routeIs('reports.*') ? 'open' : '' }}" style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="menu-laporan" class="nav-submenu {{ request()->routeIs('reports.*') ? 'open' : '' }}">
                <a href="{{ route('reports.index') }}" class="nav-sub-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                    <svg class="shrink-0" style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="nav-text">Laporan Harian</span>
                </a>
                <a href="{{ route('reports.rekap') }}" class="nav-sub-item {{ request()->routeIs('reports.rekap') ? 'active' : '' }}">
                    <svg class="shrink-0" style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="nav-text">Rekap + WA Ortu</span>
                </a>
                <a href="{{ route('reports.leaderboard') }}" class="nav-sub-item {{ request()->routeIs('reports.leaderboard') ? 'active' : '' }}">
                    <svg class="shrink-0 text-amber-400" style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="nav-text">Leaderboard Terlambat</span>
                </a>
            </div>

            <!-- Data Master Group -->
            <div class="nav-group-label">Data Master</div>

            <a href="{{ route('students.index') }}"
               title="Data Siswa"
               class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="nav-text">Data Siswa</span>
            </a>

            @role('admin')
            <a href="{{ route('teachers.index') }}"
               title="Data Guru / Dosen"
               class="nav-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="nav-text">Data Guru / Dosen</span>
            </a>
            @endrole

            <a href="{{ route('classes.index') }}"
               title="Data Kelas"
               class="nav-item {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="nav-text">Data Kelas</span>
            </a>

            @role('admin')
            <!-- Pengaturan Group -->
            <div class="nav-group-label">Pengaturan</div>

            <a href="{{ route('settings.index') }}"
               title="Aturan Jam Masuk"
               class="nav-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-text">Aturan Jam Masuk</span>
            </a>

            <a href="{{ route('holidays.index') }}"
               title="Kalender Libur Sekolah"
               class="nav-item {{ request()->routeIs('holidays.*') ? 'active' : '' }}">
                <svg class="shrink-0 text-emerald-400" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="nav-text">Kalender Libur</span>
            </a>

            <a href="{{ route('settings.school') }}"
               title="Konfigurasi Aplikasi"
               class="nav-item {{ request()->routeIs('settings.school') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-text">Konfigurasi Aplikasi</span>
            </a>

            <a href="{{ route('devices.index') }}"
               title="Manajemen Device RFID"
               class="nav-item {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m14-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                <span class="nav-text">Device RFID</span>
            </a>

            <a href="{{ route('users.index') }}"
               title="Manajemen Pengguna"
               class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="nav-text">Manajemen Pengguna</span>
            </a>

            <a href="{{ route('integrations.index') }}"
               title="Integrasi API Eksternal"
               class="nav-item {{ request()->routeIs('integrations.*') ? 'active' : '' }}">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="nav-text">Integrasi API</span>
            </a>

            <a href="{{ route('activity-logs.index') }}"
               title="Audit Trail / Activity Log"
               class="nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <svg class="shrink-0 text-amber-400" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-text">Audit Trail Log</span>
            </a>
            @endrole

            <!-- Bantuan Group -->
            <div class="nav-group-label">Bantuan & Panduan</div>
            <a href="{{ route('guide') }}"
               title="Panduan Aplikasi"
               class="nav-item {{ request()->routeIs('guide') ? 'active' : '' }}">
                <svg class="shrink-0 text-indigo-400" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="nav-text">Panduan Aplikasi</span>
            </a>

            <!-- Kiosk & Layar Publik -->
            <div class="nav-group-label">Layar Publik & Proyektor</div>
            <a href="{{ route('kiosk.scan') }}" target="_blank"
               title="Buka Mode Kiosk"
               class="nav-item">
                <svg class="shrink-0" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="nav-text flex-1">Mode Kiosk Scanner</span>
                <svg class="shrink-0 opacity-40 chevron" style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <a href="{{ route('public.leaderboard') }}" target="_blank"
               title="Leaderboard Publik (Proyektor)"
               class="nav-item">
                <svg class="shrink-0 text-amber-400" style="width:19px;height:19px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="nav-text flex-1">Leaderboard Publik</span>
                <svg class="shrink-0 opacity-40 chevron" style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </nav>

        <!-- User Card at Bottom -->
        <div class="mt-auto px-3 py-4 border-t border-slate-800 user-card">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-slate-800 transition group">
                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info min-w-0 flex-1">
                    <div class="text-white font-semibold text-sm truncate">{{ Auth::user()->name }}</div>
                    <div class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</div>
                </div>
                <div class="relative user-info" x-data="{ open: false }">
                    <button @click="open = !open" class="text-slate-400 hover:text-white transition p-1.5 rounded-lg hover:bg-slate-700">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute bottom-full right-0 mb-2 w-44 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                            <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                <svg style="width:15px;height:15px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ====== MAIN CONTENT ====== -->
    <div id="main-content" class="main-content">

        <!-- Top Bar -->
        <div class="topbar">
            <!-- Sidebar Hamburger Toggle Button (Desktop & Mobile) -->
            <button id="sidebar-toggle-btn" onclick="toggleSidebar()" class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition flex items-center gap-2" title="Toggle Sidebar">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Dynamic Breadcrumbs Navigation -->
            <nav class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-500 overflow-x-auto whitespace-nowrap ml-2 mr-4">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>

                @if(request()->routeIs('dashboard'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-bold">Dashboard</span>
                @elseif(request()->routeIs('students.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Data Master</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('students.index') }}" class="hover:text-indigo-600 font-medium">Data Siswa</a>
                    @if(request()->routeIs('students.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Siswa</span>
                    @elseif(request()->routeIs('students.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Siswa</span>
                    @elseif(request()->routeIs('students.import'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Import Siswa</span>
                    @endif
                @elseif(request()->routeIs('teachers.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Data Master</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('teachers.index') }}" class="hover:text-indigo-600 font-medium">Data Guru</a>
                    @if(request()->routeIs('teachers.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Guru</span>
                    @elseif(request()->routeIs('teachers.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Guru</span>
                    @elseif(request()->routeIs('teachers.import'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Import Guru</span>
                    @endif
                @elseif(request()->routeIs('classes.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Data Master</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('classes.index') }}" class="hover:text-indigo-600 font-medium">Data Kelas</a>
                    @if(request()->routeIs('classes.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Kelas</span>
                    @elseif(request()->routeIs('classes.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Kelas</span>
                    @endif
                @elseif(request()->routeIs('reports.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Laporan</span>
                    <span class="text-slate-300">/</span>
                    @if(request()->routeIs('reports.index'))
                        <span class="text-slate-800 font-bold">Laporan Harian</span>
                    @elseif(request()->routeIs('reports.rekap'))
                        <span class="text-slate-800 font-bold">Rekap Presensi</span>
                    @elseif(request()->routeIs('reports.leaderboard'))
                        <span class="text-slate-800 font-bold">Leaderboard Terlambat</span>
                    @endif
                @elseif(request()->routeIs('devices.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Pengaturan</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('devices.index') }}" class="hover:text-indigo-600 font-medium">Device RFID</a>
                    @if(request()->routeIs('devices.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Device</span>
                    @elseif(request()->routeIs('devices.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Device</span>
                    @endif
                @elseif(request()->routeIs('users.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Pengaturan</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('users.index') }}" class="hover:text-indigo-600 font-medium">Manajemen Pengguna</a>
                    @if(request()->routeIs('users.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Pengguna</span>
                    @elseif(request()->routeIs('users.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Pengguna</span>
                    @endif
                @elseif(request()->routeIs('integrations.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Pengaturan</span>
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('integrations.index') }}" class="hover:text-indigo-600 font-medium">Integrasi API</a>
                    @if(request()->routeIs('integrations.create'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Tambah Integrasi</span>
                    @elseif(request()->routeIs('integrations.edit'))
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">Edit Integrasi</span>
                    @endif
                @elseif(request()->routeIs('guide'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-bold">Panduan Aplikasi</span>
                @elseif(request()->routeIs('settings.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-500">Pengaturan</span>
                    <span class="text-slate-300">/</span>
                    @if(request()->routeIs('settings.index'))
                        <span class="text-slate-800 font-bold">Jam Presensi</span>
                    @elseif(request()->routeIs('settings.school'))
                        <span class="text-slate-800 font-bold">Konfigurasi Sekolah</span>
                    @endif
                @elseif(request()->routeIs('attendances.*'))
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-bold">Presensi Harian</span>
                @endif
            </nav>

            <!-- Right Side Topbar -->
            <div class="flex items-center gap-3 ml-auto">
                <!-- Current Time -->
                <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 font-medium">
                    <svg style="width:13px;height:13px;color:#6366f1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="topbar-clock" class="font-mono"></span>
                </div>

                <!-- Quick Kiosk Link -->
                <a href="{{ route('kiosk.scan') }}" target="_blank"
                   class="flex items-center gap-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-3.5 py-2 rounded-xl transition shadow-sm">
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Mode Kiosk</span>
                </a>

                <!-- User Avatar -->
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Page Header (slot) -->
        @isset($header)
            <div class="px-6 pt-6 pb-0">
                <div class="fade-in">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="flex-1 px-6 py-6 fade-in">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    @if($schoolSettings['logo_url'])
                        <img src="{{ $schoolSettings['logo_url'] }}" class="object-contain opacity-70" style="max-height:22px; max-width:60px;">
                    @endif
                    <span class="text-xs text-slate-500 font-medium">
                        {{ $schoolSettings['footer_text'] }}
                    </span>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-400 font-medium">
                    @if($schoolSettings['school_phone'])
                        <span>Telp: {{ $schoolSettings['school_phone'] }}</span>
                    @endif
                    @if($schoolSettings['school_email'])
                        <span>Email: {{ $schoolSettings['school_email'] }}</span>
                    @endif
                    @if($schoolSettings['school_address'])
                        <span class="hidden md:inline">{{ $schoolSettings['school_address'] }}</span>
                    @endif
                </div>
            </div>
        </footer>
    </div>

    <!-- Vendor JS: jQuery, Select2, SweetAlert2 - served locally (Offline Ready) -->
    <script src="{{ asset('vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2.all.min.js') }}"></script>
    <script>
        // Topbar clock
        function updateClock() {
            const now = new Date();
            const el = document.getElementById('topbar-clock');
            if (el) {
                el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Submenu toggle
        function toggleSubmenu(id, chevId) {
            const menu = document.getElementById(id);
            const chev = document.getElementById(chevId);
            if (menu) menu.classList.toggle('open');
            if (chev) chev.classList.toggle('open');
        }

        // Responsive Hamburger & Desktop Collapse Toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        // Restore desktop collapse preference
        if (window.innerWidth > 768) {
            if (localStorage.getItem('sidebar_collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
            }
        }

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                const isOpen = sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('active', isOpen);
            } else {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed', isCollapsed);
                localStorage.setItem('sidebar_collapsed', isCollapsed);
            }
        }

        // --- SWEETALERT2 INTEGRATION ---
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3500,
                timerProgressBar: true,
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100'
                }
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#f59e0b',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100'
                }
            });
        @endif

        // Global SweetAlert Delete Confirmation
        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
        // Global AJAX Search for Tables
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form[method="GET"]').forEach(form => {
                const searchInput = form.querySelector('input[name="search"]');
                if (searchInput) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        window.performAjaxSearch(form);
                    });
                }
            });
        });

        window.performAjaxSearch = async function(form) {
            const tableContainer = document.querySelector('.page-card .overflow-x-auto');
            if (tableContainer) tableContainer.style.opacity = '0.5';

            const url = new URL(form.action);
            const params = new URLSearchParams(new FormData(form));
            url.search = params.toString();

            window.history.pushState({}, '', url);

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update Table
                const newTable = doc.querySelector('.page-card .overflow-x-auto');
                if (newTable && tableContainer) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }

                // Update Pagination
                const oldPagination = document.querySelector('.page-card > .px-6.py-4.border-t');
                const newPagination = doc.querySelector('.page-card > .px-6.py-4.border-t');
                
                if (newPagination && oldPagination) {
                    oldPagination.innerHTML = newPagination.innerHTML;
                } else if (newPagination && !oldPagination) {
                    tableContainer.insertAdjacentHTML('afterend', newPagination.outerHTML);
                } else if (!newPagination && oldPagination) {
                    oldPagination.remove();
                }

                // Update Total Counter (It is right after the form)
                const oldTotal = form.nextElementSibling;
                const newForm = doc.querySelector('form');
                if (newForm && newForm.nextElementSibling && oldTotal) {
                    oldTotal.innerHTML = newForm.nextElementSibling.innerHTML;
                }
                
                // Re-bind any necessary scripts if needed (like feather icons, etc)
                // We re-execute scripts found in the new table if any, but it's pure HTML for now.
            } catch(e) {
                console.error("AJAX Search Error:", e);
                // Fallback to standard submit
                form.submit();
            }
            
            if (tableContainer) tableContainer.style.opacity = '1';
        }
    </script>

    @stack('scripts')
</body>
</html>
