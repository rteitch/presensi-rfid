# 🏫 RTH NEXUS — Sistem Presensi RFID & Manajemen Sekolah Terintegrasi

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge)](LICENSE)
[![Tests](https://img.shields.io/badge/Tests-101%20Passing-22c55e?style=for-the-badge&logo=checkmarx)](tests/)

**RTH NEXUS Presensi RFID** adalah platform sistem presensi siswa mandiri berbasis pemindai kartu RFID (*Radio Frequency Identification*) yang dirancang khusus untuk sekolah dan institusi pendidikan modern.

Sistem ini terintegrasi dengan layar **Kiosk Standalone**, papan peringkat kedisiplinan (**Leaderboard Realtime**), pengiriman rekap presensi langsung ke **WhatsApp orang tua/wali**, serta manajemen hak akses bertingkat untuk **Administrator**, **Guru Wali Kelas**, dan **Kepala Sekolah**.

---

## ✨ Fitur Utama

### 1. 📺 Kiosk Pemindai RFID (Self-Service Attendance Kiosk)
- **Automatic Card Scanner**: Siswa tap kartu RFID untuk presensi *masuk* atau *pulang*
- **Text-to-Speech (TTS)**: Kiosk membaca nama siswa dan status presensi secara lisan (`"Selamat datang, Ahmad Fauzan — Hadir"`) menggunakan Web Speech API
- **HTML5 Audio Synthesizer**: Efek suara double-chime (sukses) dan buzzer (gagal/terlambat) tanpa file MP3 eksternal
- **Hardware Debouncer**: Anti-double-scan 2 detik mencegah pemindaian ganda tidak sengaja
- **Tampilan Visual**: Animasi status card, countdown reset 4 detik, shortcut ke Leaderboard Publik

### 2. 🔐 Role-Based Access Control (RBAC) & Multi-Role System
- **ADMINISTRATOR**: Akses penuh — Data Master, Pengaturan Sekolah, Device RFID, Audit Log, User, Rate Limit
- **GURU / WALI KELAS**: Akses otomatis di-scope hanya untuk kelas binaannya sendiri
- **KEPALA SEKOLAH**: View-only bebas untuk monitoring seluruh kelas tanpa batasan

### 3. 📋 7 Status Presensi Terpisah
Sistem mendukung **7 status presensi** yang masing-masing memiliki warna badge, laporan, dan rekap tersendiri:

| Status | Warna | Keterangan |
|---|---|---|
| ✅ **Hadir** | Hijau | Masuk tepat waktu via RFID |
| ⏰ **Terlambat** | Kuning | Masuk melebihi toleransi waktu |
| 📋 **Izin** | Biru | Izin resmi ada surat/keterangan |
| 🚶 **Pulang Cepat** | Cyan | Izin keluar sebelum jam selesai |
| 🏆 **Dispensasi** | Teal | Kegiatan resmi sekolah / lomba / OSIS |
| 🤒 **Sakit** | Ungu | Sakit dengan keterangan |
| ❌ **Alpha** | Merah | Tanpa keterangan / bolos |

### 4. 📝 Input Presensi Manual — Multi-Siswa (Select2)
- **Select2 Multi-select**: Pilih banyak siswa sekaligus untuk input massal (dispensasi lomba, izin rombongan)
- Siswa dikelompokkan per kelas dalam dropdown dengan pencarian realtime
- Guru hanya bisa menginput untuk siswa di kelasnya sendiri (IDOR protection)
- **Overwrite otomatis**: Jika siswa sudah ada record di hari tersebut, data diperbarui

### 5. 📊 Laporan & Rekap Lengkap
- **Laporan Harian** (`/reports`): Filter bulan + kelas, badge warna per status, export PDF & Excel
- **Rekap Per Siswa** (`/reports/rekap`): 7 kolom status, tombol WA Ortu otomatis, highlight siswa bermasalah
- **Leaderboard Kedisiplinan**: Podium Top 3, peringkat lengkap, tombol WA massal ke orang tua
- **Leaderboard Publik** (tanpa login): Bisa dipasang di layar TV sekolah
- **Export Excel (.xlsx)**: Rekap harian & per-siswa dengan kolom semua status
- **Export PDF (.pdf)**: Laporan cetak dengan warna status berbeda tiap kategori

### 6. 🔒 Enterprise Security & Monitoring
- **Device Token Middleware**: Endpoint `/api/rfid/scan` dilindungi `X-Device-Token`
- **Rate Limiting API**: Konfigurasi batas request API pihak ketiga via UI Admin
- **Audit Trail** (`/activity-logs`): Rekam jejak semua perubahan data (old vs new value, IP, User Agent)
- **Health Check** (`/api/health`): Status DB, Cache, dan environment real-time
- **Anti-Cache Corruption**: Safeguard otomatis mendeteksi dan flush cache yang korup

### 7. 🗓️ Manajemen Hari Sekolah & Libur
- **Hari Efektif**: Konfigurasi hari sekolah (5 hari, 6 hari, atau Pesantren/Jumat libur)
- **Kalender Libur**: Tambah/edit/hapus agenda libur nasional & sekolah
- **Auto-Block Scan**: Pemindaian RFID pada hari libur otomatis ditolak
- **Auto-Mark Alpha** (`attendance:auto-alpha`): Cron harian jam 17:00 — siswa tanpa scan otomatis di-alpha

### 8. 📱 Notifikasi WhatsApp Orang Tua
- Tombol WA langsung dari halaman Rekap Per Siswa dan profil siswa
- Pesan terformat Bahasa Indonesia dengan rekap lengkap 7 status
- Tombol WA massal Top 3 Leaderboard untuk orang tua siswa paling terlambat

### 9. 📁 Data Master Lengkap
- **Siswa**: Foto, NIS, RFID UID, kontak orang tua, riwayat presensi
- **Guru**: NIP, kelas binaan, mata pelajaran, kontak
- **Kelas**: Rekap statistik, export Excel/PDF rekap kelas
- **Import Siswa**: Upload Excel massal dari template standar

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP 8.3+, Composer, Node.js 20+
- MySQL 8.0+ / MariaDB 10.6+
- Docker & Docker Compose (opsional, direkomendasikan)

### Via Docker (Direkomendasikan)
```bash
git clone https://github.com/rteitch/presensi-rfid.git
cd presensi-rfid

# Copy environment
cp .env.example .env

# Start containers
docker-compose up -d

# Setup aplikasi
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install && npm run build
```

### Setup Manual
```bash
composer install
cp .env.example .env
php artisan key:generate

# Edit .env: DB_*, APP_URL
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

### Buat Akun Admin Pertama
```bash
# Via Artisan Command
php artisan make:admin "Nama Admin" admin@sekolah.sch.id password123

# Atau via Database Seeder (sudah otomatis saat migrate --seed)
# Email: admin@example.com | Password: password
```

---

## ⚙️ Konfigurasi Hardware RFID

### Opsi 1: Microcontroller / IoT Device
```
POST /api/rfid/scan
Header: X-Device-Token: {token_dari_admin}
Body: { "rfid_uid": "04C3D4E5" }
```

### Opsi 2: USB RFID Reader (Keyboard Emulation)
1. Buka `/kiosk/scan` di browser kiosk (fullscreen)
2. Arahkan fokus ke halaman kiosk
3. Tempel kartu — reader otomatis mengetik UID dan submit

### Konfigurasi Device Token
Buka **Admin → Pengaturan → Perangkat RFID** → Tambah device baru → Salin token.

---

## 🏗️ Arsitektur Teknis

```
presensi-rfid/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AttendanceController.php    # Presensi manual & index
│   │   │   ├── DashboardController.php     # Statistik + 7 status breakdown
│   │   │   ├── ReportController.php        # Laporan harian, rekap, leaderboard
│   │   │   └── Api/RfidScanController.php  # Endpoint scan RFID
│   │   ├── Middleware/
│   │   │   └── DeviceTokenMiddleware.php   # Proteksi API endpoint
│   │   └── Requests/
│   │       └── StoreAttendanceManualRequest.php  # Validasi 7 status + array student_id
│   ├── Models/
│   │   ├── Attendance.php      # Status: hadir|terlambat|izin|pulang_cepat|dispensasi|sakit|alpha
│   │   ├── AttendanceSetting.php   # Cache jam masuk/pulang + anti-korupsi cache
│   │   ├── Student.php
│   │   └── SchoolClass.php
│   └── Console/Commands/
│       └── AutoMarkAlpha.php   # Scheduler harian
├── database/migrations/
│   └── ...                     # Termasuk enum 7 status (2026_07_25_*)
├── Exports/
│   ├── DailyAttendanceExport.php
│   └── RekapAttendanceExport.php   # 7 kolom status (termasuk Pulang Cepat & Dispensasi)
└── resources/views/
    ├── kiosk/scan.blade.php    # TTS + audio feedback
    ├── attendances/index.blade.php # Select2 multi-student manual input
    ├── reports/
    │   ├── index.blade.php     # Laporan harian + badge 7 status
    │   ├── rekap.blade.php     # Rekap per siswa + WA ortu + 7 kolom
    │   └── leaderboard.blade.php
    └── dashboard.blade.php     # Stat cards + breakdown tidak hadir
```

---

## 🧪 Test Suite

```bash
# Jalankan semua 101 test
php artisan test

# Atau via Docker
docker-compose exec -T -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test
```

**Coverage**: 101 Tests, 240+ Assertions — Authentication, RBAC, RFID API, Manual Attendance, Reports, Import/Export, Auto-Alpha, Rate Limiting, Security (IDOR), Race Condition.

---

## 📋 Status Badge Color Map

| Status | Badge CSS Class | Warna |
|---|---|---|
| hadir | `badge-green` | #22c55e |
| terlambat | `badge-amber` | #f59e0b |
| izin | `badge-blue` | #3b82f6 |
| pulang_cepat | `badge-cyan` | #06b6d4 |
| dispensasi | `badge-teal` | #14b8a6 |
| sakit | `badge-indigo` | #6366f1 |
| alpha | `badge-red` | #ef4444 |

---

## 🔄 Changelog Terbaru

### v2.5.0 — Juli 2026
- ✅ **Tambah 2 Status Presensi Baru**: `pulang_cepat` dan `dispensasi` (migration + semua laporan)
- ✅ **Select2 Multi-Student**: Form manual presensi mendukung pilih banyak siswa sekaligus
- ✅ **TTS Audio Feedback**: Kiosk membaca status presensi secara lisan (Web Speech API)
- ✅ **Dashboard Breakdown**: Card "Tidak Hadir" menampilkan breakdown 5 sub-status
- ✅ **Cache Corruption Guard**: Auto-flush cache korup di `AttendanceSetting`
- ✅ **Rekap WA Orang Tua**: Pesan WhatsApp sekarang menyertakan Pulang Cepat & Dispensasi
- ✅ **Export Excel/PDF**: Kolom baru untuk semua 7 status di semua format export

---

## 📄 Lisensi

[MIT License](LICENSE) — RTH NEXUS © 2026
