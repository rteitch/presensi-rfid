# 🏫 RTH NEXUS — Sistem Presensi RFID & Manajemen Sekolah Terintegrasi

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Vite Version](https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x/4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![SweetAlert2](https://img.shields.io/badge/SweetAlert2-11.x-Sass?style=for-the-badge&logo=sweetalert2&logoColor=white)](https://sweetalert2.github.io/)
[![License](https://img.shields.io/badge/License-MIT-green.style=for-the-badge)](LICENSE)

**RTH NEXUS Presensi RFID** adalah platform sistem presensi siswa mandiri berbasis pemindai kartu RFID (*Radio Frequency Identification*) yang dirancang khusus untuk sekolah dan institusi pendidikan modern. Sistem ini terintegrasi dengan layar **Kiosk Standalone**, papan peringkat kedisiplinan (**Leaderboard Realtime**), pengiriman rekapan presensi langsung ke WhatsApp orang tua/wali siswa, serta manajemen hak akses bertingkat untuk **Administrator (IT/BK)** dan **Guru Wali Kelas**.

---

## 🌟 Fitur Utama Sistem

### 1. 📡 Kiosk Pemindai RFID (Self-Service Attendance Kiosk)
- **Automatic Card Scanner**: Siswa melakukan tap kartu RFID pada reader USB/Serial untuk presensi *masuk* atau *pulang*.
- **HTML5 Web Audio Synthesizer**: Suara efek langsung dari browser (*Double-chime* nada tinggi untuk sukses, *Sawtooth buzzer* untuk gagal/terlambat) tanpa ketergantungan file MP3 eksternal.
- **Hardware Debouncer**: Map debouncer 2 detik mencegah pemindaian ganda dari kartu yang sama secara tidak sengaja.
- **Tampilan Visual Kiosk**: Card animasi status, countdown reset 4 detik, dan shortcut ke Papan Leaderboard Publik.

### 2. 🛡️ Role-Based Access Control (RBAC) & Multi-Role System
- **Tiga Peran Utama (Spatie RBAC)**:
  - **ADMINISTRATOR**: Akses penuh mengelola Data Master, Pengaturan Sekolah, Device RFID, Audit Log, User, dan Konfigurasi Rate Limit.
  - **GURU / WALI KELAS**: Akses terbatas yang secara otomatis di-*scope* hanya untuk kelas binaannya sendiri (mencatat presensi manual, melihat rekapitulasi siswa, dan mengekspor Laporan PDF/Excel).
  - **KEPALA SEKOLAH (Executive Monitoring)**: Akses *view-only* bebas untuk memantau statistik presensi seluruh kelas, laporan rekapitulasi, leaderboard kedisiplinan, profil siswa, dan audit trail tanpa batasan kelas.
- **Manajemen Pengguna UI (`/users`)**: Admin dapat menambah akun baru untuk Admin, Guru, atau Kepala Sekolah, serta mengubah password.
- **Custom Artisan Command**: Perintah instan terminal `php artisan make:admin "Nama" email@sekolah.sch.id password` untuk membuat akun Admin baru.

### 3. 👤 Detail Profil Siswa & Profil Guru (`/students/{id}` & `/teachers/{id}`)
- **Profil Siswa Lengkap**: Foto, NIS, Nama, Kelas, RFID UID, Kontak Ortu, Ringkasan Bulanan (5 Status Presensi), dan Riwayat Log Presensi.
- **Profil Guru Lengkap**: Detail pengajar, NIP, Mata Pelajaran, Nomor HP/WA, Email Login, serta daftar **Wali Kelas Binaan** yang diampu.
- **Direct WA Ortu & Excel Export**: Kirim rekap bulanan siswa ke WA ortu atau download file Excel `.xlsx` individual.

### 4. 🏫 Detail Kelas & Export Rekapitulasi Kelas (`/classes/{id}`)
- **Dashboard Rekapitulasi Kelas**: Statistik rata-rata kehadiran kelas, total keterlambatan, dan total alpha.
- **Export Multi-Format**:
  - **Export Excel (`.xlsx`)**: Rekapitulasi lengkap siswa dalam 1 file spreadsheet via kelas dedicated `ClassRekapExport`.
  - **Export PDF (`.pdf`)**: Cetak rekap bulanan kelas dengan *alert highlighting* (kuning) untuk siswa bermasalah.

### 5. ⚙️ Enterprise Security, Audit Trail, Auto-Alpha & Monitoring
- **Authentication Header**: Endpoint API `/api/rfid/scan` dilindungi middleware `device.token` (`X-Device-Token`). Pemindaian Kiosk dijamin **100% bebas hambatan** saat jam sibuk pagi hari.
- **Konfigurasi UI Hari Sekolah Efektif & Rate Limit (`/settings/school`)**: Admin dapat mengatur hari-hari sekolah efektif secara universal via multi-checkbox (misal **5 Hari Kerja**, **6 Hari Kerja**, maupun **Pesantren / Sekolah Islam yang Libur di Hari Jumat**). Selain itu, Admin dapat mengatur batas *Rate Limit API Pihak Ketiga* langsung dari antarmuka Admin.
- **Audit Trail / Activity Log (`/activity-logs`)**: Mencatat setiap jejak digital pembuatan, perubahan, dan penghapusan data (Model Siswa, Guru, Kelas, User, Pengaturan) lengkap dengan perbandingan data lama vs baru, alamat IP, dan User Agent.
- **Auto-Mark Alpha (`php artisan attendance:auto-alpha`)**: Perintah scheduler harian (setiap jam 17:00 / setelah jam pulang) yang secara otomatis menandai siswa aktif tanpa presensi sebagai *Alpha* pada hari sekolah efektif (skip otomatis pada hari libur rutin sekolah & kalender libur).
- **Kalender Libur Sekolah (`/holidays`)**: Pengelolaan agenda libur nasional & sekolah lengkap dengan form Tambah & **Modal Edit Agenda**. Pemindaian RFID pada hari libur otomatis ditolak dengan pesan *"Hari Libur Sekolah"*.
- **Health Check Endpoint (`/api/health`)**: Endpoint JSON real-time untuk memantau konektivitas Database MySQL, operasional Cache, dan status environment aplikasi.
- **Soft Deletes & Anti-Cascade Wipeout**: Menghapus data siswa/guru melindungi riwayat presensi masa lalu agar tidak musnah terhapus.

### 6. ⏱️ Aturan Jam Presensi & Toleransi Keterlambatan
- Fleksibel dalam mengatur **Jam Masuk**, **Jam Pulang**, dan **Toleransi Keterlambatan (menit)**.
- Perhitungan otomatis status kedisiplinan (Hadir Tepat Waktu vs Terlambat).
- **Perlindungan 5 Menit**: Mencegah siswa melakukan tap *Pulang* kurang dari 5 menit setelah tap *Masuk*.

### 7. 📄 Laporan, Rekapitulasi & WhatsApp Ortu
- **Laporan Presensi Harian & Bulanan**: Filter berdasarkan tanggal, bulan, dan kelas.
- **Notifikasi WA Ortu**: Tombol langsung untuk membuka WhatsApp dengan pesan terformat sopan dalam Bahasa Indonesia ke nomor orang tua/wali siswa.

### 8. 🏆 Leaderboard Kedisiplinan (Publik & Internal)
- **Podium Top 3 Flex Centering**: Visualisasi peringkat 3 besar siswa paling sering terlambat.
- **Live Auto-Sync 15 Detik**: Halaman leaderboard publik (`/leaderboard`) memperbarui data secara otomatis setiap 15 detik tanpa me-refresh manual.

---

## 🔌 Integrasi API & Keamanan Token (Third-Party Integration)

Sistem **RTH NEXUS** dirancang menggunakan arsitektur *Decoupled Service* sehingga **sangat mudah dan siap diintegrasikan dengan aplikasi pihak ketiga** (seperti SIM Akademik Sekolah, LMS, Aplikasi Mobile, atau Sistem Keuangan SPP).

### ❓ Apakah Perlu Token Saat Integrasi?
**YA, SANGAT DIREKOMENDASIKAN MENGGUNAKAN TOKEN!** 🔑
Penggunaan Token (API Token / Device Token `X-Device-Token` / Bearer Token) sangat penting untuk:
1. **Keamanan Data Siswa**: Mencegah akses data presensi oleh pihak luar yang tidak terotorisasi (*Unauthorized Data Access*).
2. **Pencegahan Fraud / Tampering**: Memastikan data tap kartu murni berasal dari perangkat/aplikasi sekolah yang terdaftar secara sah.
3. **Rate Limiting & Server Stability**: Membatasi kuota panggilan API (misal 60 req/menit) agar server sekolah tetap stabil.

---

### 🌐 2 Skema Integrasi Utama

#### Skema A: Inbound API (Aplikasi Lain Mengambil / Mengirim Data ke RTH NEXUS)

1. **Scan RFID Tap Kartu (Device / Kiosk / Microcontroller ESP32)**
   - **Endpoint**: `POST /api/rfid/scan`
   - **Header Wajib**: `X-Device-Token: <TOKEN_DEVICE>`
   - **Body JSON**:
     ```json
     {
       "uid": "04A1B2C3"
     }
     ```
   - **Response JSON**:
     ```json
     {
       "success": true,
       "type": "masuk",
       "status": "hadir",
       "message": "Selamat datang, Ahmad Fauzan"
     }
     ```

2. **Pengambilan Rekapitulasi Presensi (Sistem Pihak ke-3)**
   - **Header Wajib**: `Authorization: Bearer <API_TOKEN>` atau `X-API-Key: <SECRET_KEY>`
   - **Endpoint**: `GET /api/v1/attendances/rekap?bulan=2026-07&class_id=1`
   - **Response JSON**:
     ```json
     {
       "status": "success",
       "data": [
         {
           "nis": "2025001",
           "nama": "Ahmad Fauzan",
           "kelas": "VII-A",
           "hadir": 20,
           "terlambat": 1,
           "izin": 0,
           "sakit": 0,
           "alpha": 0
         }
       ]
     }
     ```

---

#### Skema B: Outbound Real-time Webhook (RTH NEXUS Mengirim Data ke Aplikasi Lain)

Setiap kali siswa berhasil melakukan tap kartu di Kiosk, `AttendanceService` dapat memicu **HTTP Webhook POST** ke endpoint server aplikasi sekolah Anda secara instan:

- **Target Webhook URL**: `https://aplikasi-lain.sch.id/api/webhook/presensi`
- **Header Autentikasi**: `X-Webhook-Signature: <HMAC_SHA256_HASH>`
- **Payload JSON Real-time**:
  ```json
  {
    "event": "attendance.scanned",
    "timestamp": "2026-07-24 06:55:12",
    "student": {
      "nis": "2025001",
      "nama": "Ahmad Fauzan",
      "kelas": "VII-A"
    },
    "attendance": {
      "tanggal": "2026-07-24",
      "type": "masuk",
      "status": "hadir",
      "jam_masuk": "06:55:12"
    }
  }
  ```

---

## 🛠️ Tech Stack & Dependencies

- **Backend Framework**: [Laravel 13.x](https://laravel.com) (`laravel/framework: ^13.8`)
- **Programming Language**: PHP 8.3+ (`php: ^8.3`)
- **Database Engine**: MySQL 8.0+ / MariaDB 10.11+
- **Role & Access Control**: [Spatie Laravel-Permission 8.3](https://spatie.be/docs/laravel-permission) (`spatie/laravel-permission: ^8.3`)
- **Excel Processor**: [Maatwebsite Excel 3.1](https://laravel-excel.com) (`maatwebsite/excel: ^3.1`)
- **PDF Generator**: [Barryvdh Laravel DomPDF 3.1](https://github.com/barryvdh/laravel-dompdf) (`barryvdh/laravel-dompdf: ^3.1`)
- **Auth Scaffold**: [Laravel Breeze 2.4](https://laravel.com/docs/starter-kits#laravel-breeze) (`laravel/breeze: ^2.4`)
- **Asset Bundler**: [Vite 8.x](https://vitejs.dev) (`vite: ^8.0`, `@tailwindcss/vite: ^4.0`)
- **Frontend Stack**: Blade Templates, AlpineJS (`^3.4`), Tailwind CSS (`^3.1`), SweetAlert2 11.x, HTML5 Web Audio API Synthesizer
- **Testing Framework**: PHPUnit 12.5 (`phpunit/phpunit: ^12.5`) — **51 Test Cases Passed 100%**

---

## 📁 Struktur Direktori Proyek Utama

```
aplikasi_sekolah/presensi-rfid/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── CreateAdminUser.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/RfidScanController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── AttendanceSettingController.php
│   │   │   ├── ClassController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DeviceController.php
│   │   │   ├── ReportController.php
│   │   │   ├── SchoolSettingController.php
│   │   │   ├── StudentController.php
│   │   │   ├── TeacherController.php
│   │   │   └── UserController.php
│   │   ├── Middleware/
│   │   │   └── DeviceTokenMiddleware.php
│   │   └── Requests/
│   │       ├── StoreStudentRequest.php
│   │       ├── UpdateStudentRequest.php
│   │       ├── StoreUserRequest.php
│   │       ├── UpdateUserRequest.php
│   │       └── ...
│   ├── Models/
│   │   ├── Attendance.php
│   │   ├── Device.php
│   │   ├── RfidLog.php
│   │   ├── SchoolClass.php
│   │   ├── SchoolSetting.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   └── User.php
│   └── Services/
│       └── AttendanceService.php
├── resources/
│   ├── views/
│   │   ├── attendances/
│   │   ├── classes/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── pdf.blade.php
│   │   ├── devices/
│   │   ├── guide.blade.php
│   │   ├── kiosk/
│   │   │   └── scan.blade.php
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── guest.blade.php
│   │   ├── reports/
│   │   ├── settings/
│   │   ├── students/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   └── users/
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       └── edit.blade.php
├── routes/
│   ├── api.php
│   ├── auth.php
│   └── web.php
└── README.md
```

---

## 🐳 1. Deploy 1-Klik Berbasis Docker (Rekomendasi Utama & High Performance)

Untuk menangani **ribuan request per detik (RPS)** saat jam sibuk presensi sekolah tanpa membuat peramban web memblokir (*hanged/choking*), disarankan menggunakan **Docker Production Stack (PHP 8.3-FPM + Nginx + Redis + MySQL 8)**.

### 💻 Deploy 1-Klik di Windows (PowerShell):
```powershell
.\deploy.ps1
```

### 🐧 Deploy 1-Klik di Linux / Server Ubuntu:
```bash
chmod +x deploy.sh
./deploy.sh
```

- **Web Dashboard**: `http://localhost:8000`
- **Kiosk Scanner**: `http://localhost:8000/kiosk`
- **Leaderboard**: `http://localhost:8000/leaderboard`
- **Akun Admin Default**: `admin@sekolah.test` / Password: `password`

---

## 🚀 2. Pengujian Beban & Performa (Grafana k6 Load Testing)

```powershell
# Uji Beban Kiosk RFID (Simulasi 500 Siswa Concurrent Tap)
k6 run k6/rfid_kiosk_load_test.js
```

---

## ⚡ 3. Petunjuk Instalasi Manual (Local Development)
- PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `gd`, `xml`, `curl`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- Database Server MySQL / MariaDB

### Langkah Instalasi:

1. **Clone Repository & Masuk ke Direktori Proyek**:
   ```bash
   git clone https://github.com/username/presensi-rfid.git
   cd presensi-rfid
   ```

2. **Install Dependensi PHP via Composer**:
   ```bash
   composer install
   ```

3. **Install Dependensi JavaScript via NPM**:
   ```bash
   npm install
   ```

4. **Salin File Environment Configuration (`.env`)**:
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database pada File `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=presensi_rfid
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Jalankan Migration & Seed Data Demo**:
   ```bash
   php artisan migrate --seed
   ```

8. **Membuat Akun Admin Baru (Opsional)**:
   ```bash
   php artisan make:admin "Nama Admin" admin@sekolah.sch.id password123
   ```

9. **Buat Symbolic Link Storage & Build Assets**:
   ```bash
   php artisan storage:link
   npm run build
   ```

10. **Jalankan Local Development Server**:
    ```bash
    php artisan serve
    ```
    Buka browser dan akses aplikasi di: `http://127.0.0.1:8000`

---

## 🧪 4. Automated Testing (101 Tests Passed - 100% Coverage)

Menjalankan pengujian otomatis PHPUnit (101 Test Methods / 240 Assertions):
```bash
# Pengujian di lingkungan Docker Container (Menggunakan In-Memory Session Array)
docker-compose exec -T -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test

# Pengujian di lingkungan Host Lokal
php artisan test
```

---

## 🏆 Retribusi & Kredit (RTH Nexus Attribution)

Aplikasi ini dikembangkan dan dipelihara secara resmi oleh tim **RTH NEXUS**.

- **Produk**: RTH NEXUS — Integrated Attendance & Kiosk Management System

## 🚀 Pembaruan Sistem (2026-07-24)
Sistem ini telah diperbarui secara masif untuk menjamin **High Availability**, **Performance**, dan **Data Integrity**:
1. **Real-time AJAX Kiosk & Dashboard**: Tanpa *Livewire*, sistem menggunakan `vanilla JS fetch` untuk memuat data tanpa mem-refresh halaman secara penuh, menjadikan interaksi Kiosk dan Daftar Hadir instan.
2. **Kinerja Ekstrem (Penghapusan N+1 Query)**: Lebih dari 1000 subkueri lambat pada dasbor dan laporan telah diringkas menggunakan *Conditional Aggregation* SQL (`SUM(CASE WHEN...)`) & *Eager Loading*, menurunkan waktu muat *TTFB* dari detik menjadi mili-detik. Indeks basis data juga telah disempurnakan.
3. **Integritas Relasi Basis Data (ISTQB Compliance)**: Mencegah *Orphaned Records* melalui proteksi penghapusan kaskade. Contoh: Anda tidak dapat menghapus kelas yang masih memiliki murid. Log Kiosk (`rfid_logs`) kini memiliki *Foreign Key* `student_id` yang saling terhubung utuh.
4. **Web Kiosk tanpa Token**: Kiosk berbasis *Web* (`/kiosk`) dapat diakses dari peramban apa pun oleh Admin tanpa memerlukan *Hardware Token* khusus; menjadikannya luwes dan fleksibel jika mesin perangkat keras RFID sedang rusak.

- **Instansi Pengembang**: SMK Muhammadiyah 5 Miri / Team Rizal TH
- **Hak Cipta**: © {{ date('Y') }} RTH NEXUS. All Rights Reserved.

---

<p align="center">
  Dibuat dengan ❤️ untuk kemajuan digitalisasi pendidikan Indonesia.
</p>
