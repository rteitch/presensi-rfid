# 🏫 PRESENSI RTH NEXUS

> **Sistem Manajemen Presensi Sekolah Berbasis RFID**
> Platform modern untuk pencatatan kehadiran siswa & guru menggunakan teknologi kartu RFID, dilengkapi dashboard analytics, kiosk layar sentuh, dan TV leaderboard command center.

![Laravel](https://img.shields.io/badge/Laravel-13.x-red?logo=laravel) ![PHP](https://img.shields.io/badge/PHP-8.3+-purple?logo=php) ![Docker](https://img.shields.io/badge/Docker-Ready-blue?logo=docker) ![Tests](https://img.shields.io/badge/Tests-101%20Passed-brightgreen?logo=phpunit) ![License](https://img.shields.io/badge/License-MIT-green)

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi Cepat (Docker)](#-instalasi-cepat-docker)
- [Instalasi Manual](#-instalasi-manual-tanpa-docker)
- [Struktur Akun & Peran](#-struktur-akun--peran)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [API Reference](#-api-reference)
- [Konfigurasi Pengaturan](#-konfigurasi-pengaturan)
- [Deployment Produksi](#-deployment-produksi)
- [Testing](#-testing)
- [Arsitektur Database](#-arsitektur-database)

---

## 🚀 Fitur Utama

### ✅ Presensi RFID Otomatis
- Siswa & guru tap kartu RFID → sistem otomatis mencatat jam masuk & status kehadiran
- Deteksi otomatis: **Hadir**, **Terlambat** (berdasarkan jam batas yang dikonfigurasi)
- Pencatatan jam pulang (tap kedua)
- Validasi hari efektif sekolah (bisa dikonfigurasi: Senin–Jumat atau termasuk Sabtu)

### ✅ 7 Status Kehadiran
| Status | Deskripsi | Warna |
|--------|-----------|-------|
| `hadir` | Masuk tepat waktu | 🟢 Hijau |
| `terlambat` | Masuk setelah batas jam | 🟡 Kuning |
| `izin` | Izin resmi | 🔵 Biru |
| `sakit` | Sakit dengan keterangan | 🟠 Oranye |
| `dispensasi` | Kegiatan resmi sekolah | 🟣 Ungu |
| `pulang_cepat` | Pulang sebelum jam usai | 🟤 Coklat |
| `alpha` | Tidak hadir tanpa keterangan | 🔴 Merah |

### ✅ Dashboard Analitik Real-Time
- Ringkasan harian: total hadir, terlambat, izin, alpha, dispensasi, pulang cepat, sakit
- Grafik tren kehadiran mingguan
- Top 5 siswa paling sering terlambat bulan ini
- Aktivitas presensi terbaru (live feed)
- Widget statistik per status dengan persentase
- Tombol Export PDF & Excel langsung dari dashboard

### ✅ Manajemen Data Lengkap
- **Siswa**: CRUD lengkap + jenis kelamin (L/P), agama, foto profil, RFID tag, status aktif/non-aktif, **Soft Delete + Tong Sampah (Restore & Hapus Permanen)**
- **Guru**: CRUD + foto, RFID tag, multi-kelas wali, soft delete
- **Kelas**: Manajemen kelas + wali kelas + tahun ajaran
- **Import/Export Excel**: Template siswa (termasuk L/P & agama) & guru (bulk upload)
- **Device RFID**: Manajemen perangkat reader + token keamanan

### ✅ Laporan & Export Dokumen
- Laporan presensi harian (filter bulan, kelas)
- Rekap bulanan per siswa (7 kolom status)
- Export **PDF** (DomPDF) — format resmi sekolah dengan kop
- Export **Excel** (Maatwebsite) — kompatibel Microsoft Office
- Notifikasi WhatsApp ke orang tua (template pesan siap pakai)
- Leaderboard siswa terlambat (internal admin, 20 teratas)

### ✅ Kiosk Scanner RFID (Layar Penuh)
- Halaman tap RFID tanpa login (akses publik)
- Tampilan full-screen dengan animasi modern
- Feedback visual: nama siswa, foto, status, jam masuk
- Background kustomisasi: gradient / warna solid / foto custom
- Judul & subtitle kustom per sekolah
- Pilihan multi-device reader dari dropdown

### ✅ Public Leaderboard TV (Command Center)
- Tampilan full-screen untuk dipasang di **TV Lobby / Ruang Guru**
- Grid 5×2 (10 kartu siswa terlambat terbanyak)
- Foto siswa uncropped (100% wajah terlihat, tanpa crop)
- **Frosted glass info panel 2-kolom** (Mac OS style) di bawah foto:
  - Kolom kiri: Badge keterlambatan merah solid (`12× TERLAMBAT`) dengan glow mencolok untuk keterbacaan TV dari jarak jauh
  - Kolom kanan: Nama siswa uppercase (left-aligned), chip kelas, dan keterangan tanggal `Terlambat terakhir: dd MMM YYYY`
- Chip kelas color-coded: **X = Cyan**, **XI = Emerald**, **XII = Purple**
- Trend indicator naik/turun per siswa
- Crown + glow animasi untuk Rank #1
- Clock real-time + live indicator di header
- Filter per bulan & kelas
- Auto-refresh 30 detik
- 2 mode judul: *Monitoring Kedisiplinan* atau *Hall of Shame*
- Mode privasi nama: Nama Penuh atau Inisial

### ✅ Sistem Pengguna & Peran (RBAC)
- Autentikasi via Laravel Breeze (session-based)
- 3 peran berbeda: `admin`, `guru`, `kepala_sekolah`
- Guru dibatasi hanya melihat data kelas yang diampu
- Kepala sekolah: akses read-only seluruh data

### ✅ API Integration
- REST API dengan autentikasi **X-API-Key** (bukan session)
- Rate limiting per IP yang dapat dikonfigurasi
- Endpoint rekap presensi & riwayat siswa
- Health check endpoint untuk monitoring uptime
- Manajemen API Key dari panel admin

### ✅ Fitur Operasional Tambahan
- **Input Manual**: Guru bisa menambah absensi manual (multi-select siswa dengan Select2)
- **Hari Libur Nasional**: Tambah tanggal libur, sistem otomatis tidak mencatat pada hari tersebut
- **Tahun Ajaran**: Manajemen multi tahun ajaran (aktif/arsip)
- **Activity Log**: Rekam jejak semua aksi admin (audit trail)
- **Panduan Pengguna**: Halaman `/guide` built-in, bisa diakses tanpa koneksi internet

---

## 🛠 Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Framework | Laravel | 13.x |
| PHP | PHP | 8.3+ |
| Database | MySQL | 8.0 |
| Cache/Queue | Redis | 7 Alpine |
| Frontend Build | Vite + TailwindCSS | Latest |
| Auth | Laravel Breeze | 2.x |
| RBAC | Spatie Laravel Permission | 8.x |
| PDF Export | barryvdh/laravel-dompdf | 3.x |
| Excel Export | maatwebsite/excel | 3.x |
| Container | Docker + Docker Compose | — |
| JS Widget | Alpine.js + Select2 | — |
| Font | Plus Jakarta Sans (Google Fonts) | — |

---

## 💻 Persyaratan Sistem

### Dengan Docker (Direkomendasikan)
- **Docker Desktop** 24.x+
- **Docker Compose** v2+
- RAM minimal **2 GB** (4 GB direkomendasikan)
- Disk minimal **5 GB** bebas

### Tanpa Docker (Manual)
- **PHP** 8.3+ dengan ekstensi: `pdo_mysql`, `gd`, `zip`, `intl`, `bcmath`, `mbstring`
- **MySQL** 8.0+ atau **MariaDB** 10.6+
- **Redis** 7+ (opsional, bisa fallback ke database driver)
- **Composer** 2.x
- **Node.js** 20+ & **npm** 10+

---

## ⚡ Instalasi Cepat (Docker)

### Windows (PowerShell)
```powershell
# Clone repositori
git clone https://github.com/your-org/presensi-rfid.git
cd presensi-rfid

# Jalankan script deploy otomatis (1 klik)
.\deploy.ps1
```

### Linux / macOS (Bash)
```bash
git clone https://github.com/your-org/presensi-rfid.git
cd presensi-rfid
chmod +x deploy.sh
./deploy.sh
```

Script deploy akan otomatis:
1. Menjalankan semua container Docker (app + MySQL + Redis)
2. Menjalankan migrasi database + seeder data demo
3. Membuat symlink storage
4. Menjalankan 101 automated tests sebagai verifikasi
5. Menampilkan URL akses dan kredensial admin

### Akses Setelah Deploy
| URL | Keterangan |
|-----|------------|
| `http://localhost:8000` | Dashboard Admin |
| `http://localhost:8000/kiosk` | Kiosk Scanner RFID |
| `http://localhost:8000/leaderboard` | TV Leaderboard Publik |

**Akun Default:**
```
Email    : admin@sekolah.test
Password : password
```

---

## 🔧 Instalasi Manual (Tanpa Docker)

```bash
# 1. Clone & install dependencies
git clone https://github.com/your-org/presensi-rfid.git
cd presensi-rfid
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 3. Edit .env sesuai database lokal
# DB_HOST=127.0.0.1, DB_DATABASE=presensi_rfid, dll.

# 4. Migrasi & seeder
php artisan migrate:fresh --seed

# 5. Build asset frontend
npm run build
php artisan storage:link

# 6. Jalankan server dev
composer run dev
```

---

## 👥 Struktur Akun & Peran

### Peran yang Tersedia

| Peran | Deskripsi | Batasan |
|-------|-----------|---------|
| `admin` | Akses penuh semua fitur | — |
| `guru` | Akses data kelas yang diampu saja | Tidak bisa kelola user, devices, settings |
| `kepala_sekolah` | Akses read-only semua laporan | Tidak bisa input/ubah data |

### Matriks Hak Akses

| Fitur | Admin | Guru | Kepala Sekolah |
|-------|:-----:|:----:|:--------------:|
| Dashboard | ✅ | ✅ | ✅ |
| Data Siswa (read) | ✅ | ✅ (kelas sendiri) | ✅ |
| Data Siswa (CRUD) | ✅ | ❌ | ❌ |
| Import/Export Siswa | ✅ | ❌ | ❌ |
| Data Guru (CRUD) | ✅ | ❌ | ❌ |
| Kelola Kelas | ✅ | ❌ | ❌ |
| Laporan & Export | ✅ | ✅ (kelas sendiri) | ✅ |
| Input Absensi Manual | ✅ | ✅ | ❌ |
| Kelola Device RFID | ✅ | ❌ | ❌ |
| Pengaturan Sekolah | ✅ | ❌ | ❌ |
| Manajemen User | ✅ | ❌ | ❌ |
| API Key Management | ✅ | ❌ | ❌ |
| Activity Log | ✅ | ❌ | ❌ |
| Hari Libur | ✅ | ❌ | ❌ |

### Membuat User Baru
Panel admin → **Pengguna** → **Tambah User**
> Atau via Artisan: `php artisan tinker` → `User::create([...])`

---

## 📖 Panduan Penggunaan

### 1. Setup Awal Sekolah
1. Login sebagai **admin**
2. Buka **Pengaturan → Konfigurasi Sekolah**
3. Isi identitas: nama sekolah, logo, tagline, alamat
4. Atur **Hari Efektif** (centang hari yang aktif)
5. Atur jam batas keterlambatan di **Pengaturan → Presensi**

### 2. Mendaftarkan Perangkat RFID
1. Buka **Devices** → **Tambah Device**
2. Isi nama device & lokasi pemasangan
3. Salin **Device Token** yang dihasilkan
4. Konfigurasi firmware Arduino/ESP32 dengan token tersebut

#### 🔌 Panduan Arsitektur Hardware RFID
Sistem mendukung 2 opsi arsitektur alat reader RFID:

- **Mode A: USB Desktop Reader (Plug & Play - Mode Kiosk)**
  - Tipe Reader: USB HID Emulation Keyboard (125kHz EM4100 atau 13.56MHz Mifare)
  - Pemasangan: Colok langsung ke port USB PC / Mini PC Kiosk.
  - Cara Kerja: Buka halaman `http://[server]:8000/kiosk`. Saat siswa tap kartu, reader otomatis mengetikkan UID dan memicu suara chime audio serta rekaman presensi.

- **Mode B: IoT Microcontroller Standalone (ESP32 / NodeMCU / Arduino)**
  - Modul RFID: RC522 (13.56MHz) atau RDM6300 (125kHz) via SPI/UART.
  - Koneksi: WiFi / Ethernet Shield W5500.
  - Payload POST:
    ```http
    POST /api/rfid/scan HTTP/1.1
    Host: [IP_SERVER]:8000
    X-Device-Token: [TOKEN_DEVICE_DARI_PANEL_ADMIN]
    Content-Type: application/json

    {
      "rfid_uid": "04A1B2C3",
      "device_id": "1"
    }
    ```

- **Mode C: Mobile Tablet / Smartphone Kiosk (USB OTG Portable)**
  - Konfigurasi: Tablet Android / iPad + Konverter USB OTG (Type-C / Micro USB) + USB RFID Reader.
  - Penggunaan: Menjadikan tablet/HP sebagai Kiosk Presensi Layar Sentuh Portabel tanpa membutuhkan PC Desktop.
  - Cara Kerja: Colokkan reader ke tablet via OTG, buka browser Google Chrome ke URL `http://[IP_SERVER]:8000/kiosk`. Setiap tap kartu langsung diproses real-time dengan efek audio chime. Cocok untuk pos presensi di meja piket, kelas, maupun pos gerbang luar.

#### 📍 Arsitektur Presensi Multi-Titik (Multi-Location & Multi-Kiosk)
Sistem ini dirancang untuk mendukung pencatatan presensi di **banyak lokasi secara bersamaan** (contoh: Gerbang Utama, Gerbang Barat, Lobby Gedung B, Perpustakaan, Lab Komputer):
1. **Pendaftaran Perangkat Per Titik**: Buat entri perangkat baru di menu **Devices** (`/devices`) untuk setiap titik fisik (misal: "Kiosk Gerbang Depan", "ESP32 Lobby Utama").
2. **Token Unik Per Titik**: Setiap perangkat menerima `token_device` yang unik. Hal ini memungkinkan sistem mengidentifikasi lokasi spesifik di mana siswa melakukan tap.
3. **Audit Trail Log Scan (`rfid_logs`)**: Semua aktivitas tap dari seluruh titik terekam secara terpusat di tabel `rfid_logs` beserta ID perangkat, waktu presensi, UID kartu, dan status respons API.
4. **Proteksi Double-Tap Cross-Device**: Dilengkapi *Atomic Cache Lock* (5 detik) sehingga jika siswa mencoba melakukan tap di dua titik berbeda secara bersamaan, sistem hanya memproses tap pertama dan mencegah duplikasi presensi.

### 3. Mendaftarkan Siswa
**Cara 1 — Manual:**
- Buka **Siswa** → **Tambah Siswa**
- Isi NISN, nama, kelas, tanggal lahir, nomor RFID

**Cara 2 — Import Excel (Bulk):**
1. Download template: **Siswa** → **Download Template**
2. Isi data siswa di file Excel
3. **Siswa** → **Import** → upload file

### 4. Menjalankan Kiosk RFID
1. Buka `http://[server-ip]:8000/kiosk` di browser kiosk
2. Atur browser ke mode fullscreen (F11)
3. Siswa tap kartu RFID → sistem otomatis mencatat

### 5. Memasang TV Leaderboard
1. Buka `http://[server-ip]:8000/leaderboard` di TV/monitor
2. Atur browser ke fullscreen (F11 atau Ctrl+Shift+F)
3. Layar auto-refresh setiap 30 detik

**Kustomisasi Leaderboard** (admin):
- Buka **Pengaturan → Mode Leaderboard TV**
- Pilih mode judul: *Monitoring Kedisiplinan* vs *Hall of Shame*
- Pilih privasi nama: *Nama Penuh* vs *Inisial*

### 6. Input Absensi Manual
Untuk siswa yang tidak bawa kartu / dispensasi grup:
1. Buka **Presensi** → **Input Manual**
2. Pilih tanggal, status, dan keterangan
3. Pilih siswa (multi-select, bisa cari nama)
4. Klik **Simpan**

### 7. Export Laporan
| Jenis Laporan | PDF | Excel | Lokasi |
|--------------|:---:|:-----:|--------|
| Laporan Harian | ✅ | ✅ | Menu **Laporan** |
| Rekap Bulanan per Siswa | ✅ | ✅ | **Laporan → Rekap** |
| Riwayat Siswa Individual | ✅ | — | **Siswa → Detail → Export** |
| Rekap per Kelas | ✅ | ✅ | **Kelas → Detail** |

### 8. Notifikasi WhatsApp Orang Tua
Di halaman **Rekap Bulanan**:
- Klik tombol **WhatsApp** di baris siswa
- Sistem otomatis membuka WhatsApp Web dengan template pesan rekap
- Template: nama siswa, bulan, total hadir/terlambat/izin/alpha

---

## 🔌 API Reference

### Autentikasi API
Semua endpoint API (kecuali `/health`) memerlukan header:
```
X-API-Key: [api_key_anda]
```

Buat API Key di: **Admin → Integrasi API → Tambah**

### Endpoint RFID Scanner
```
POST /api/rfid/scan
Header: X-Device-Token: [token_device]
```
```json
{
  "rfid_uid": "ABC12345",
  "device_id": "1"
}
```
**Response sukses:**
```json
{
  "success": true,
  "student": { "nama": "BUDI SANTOSO", "kelas": "X IPA 1" },
  "status": "terlambat",
  "jam_masuk": "07:35:00",
  "message": "Terlambat 5 menit"
}
```

### Endpoint Rekap Presensi (Integrasi Pihak Ketiga)
```
GET /api/v1/attendances/rekap?bulan=2026-07&class_id=1
Header: X-API-Key: [api_key]
```

### Endpoint Riwayat Siswa
```
GET /api/v1/students/{id}/history?bulan=2026-07
Header: X-API-Key: [api_key]
```

### Health Check
```
GET /api/health
```
```json
{
  "status": "ok",
  "database": "connected",
  "cache": "connected",
  "timestamp": "2026-07-25T05:00:00Z"
}
```

### Rate Limiting
| Endpoint Group | Default Limit | Configurable |
|---------------|:-------------:|:------------:|
| `/api/rfid/*` | Mengikuti `rate_limit_api` | ✅ |
| `/api/v1/*` | Mengikuti `rate_limit_api` | ✅ |
| Web routes | — | — |

> Ubah batas di: **Pengaturan → Security & Rate Limit**

---

## ⚙️ Konfigurasi Pengaturan

### Pengaturan Presensi (`/settings`)
- **Jam Masuk** — batas jam hadir normal (contoh: `07:00`)
- **Jam Batas Terlambat** — lewat jam ini = terlambat (contoh: `07:15`)
- **Jam Pulang** — jam pulang normal
- **Tahun Ajaran Aktif** — pilih atau buat tahun ajaran baru

### Konfigurasi Sekolah (`/settings/school`)
Tab 1 — **Identitas Sekolah**:
- Nama Aplikasi, Nama Sekolah, Tagline
- Upload Logo (PNG/JPG, maks 2MB)
- Alamat, Telepon, Email sekolah
- Teks footer custom

Tab 2 — **Tampilan Kiosk Scanner**:
- Judul & Subtitle kiosk
- Tipe background: Gradient / Warna Solid / Foto Upload
- Upload wallpaper kustom

Tab 3 — **Hari Sekolah Efektif**:
- Centang hari aktif (Senin–Sabtu)
- Sistem tidak akan mencatat presensi di luar hari aktif

Tab 4 — **Security & Rate Limit**:
- Batas request API per menit
- (Nilai default: 60 request/menit)

Tab 5 — **Mode Leaderboard TV**:
- Mode Judul: *Monitoring Kedisiplinan* / *Hall of Shame*
- Mode Privasi: *Nama Penuh* / *Inisial Nama*

---

### 🔒 Checklist Keamanan Deployment Online (Production Cloud / VPS)
Apakah aplikasi aman jika di-online-kan? **SANGAT AMAN**, selama checklist berikut dipenuhi:
- ✅ **`APP_DEBUG=false`**: Mencegah tereksposnya stack trace & kredensial DB saat terjadi error.
- ✅ **`APP_ENV=production`**: Mengaktifkan mode produksi Laravel.
- ✅ **`APP_URL=https://presensi.sekolah.sch.id`**: Sesuaikan dengan domain resmi ber-SSL (HTTPS).
- ✅ **Enkripsi HTTPS (SSL/TLS)**: Mengamankan lalu lintas data scan RFID, token, dan login session.
- ✅ **Autentikasi Perangkat (`X-Device-Token`)**: Setiap alat RFID di-verifikasi menggunakan token acak 40 karakter.
- ✅ **Rate Limiting Aktif**: Mencegah serangan DDoS / Brute Force pada endpoint scan API (`throttle:rfid` & `throttle:api`).
- ✅ **Role-Based Access Control (RBAC)**: Pembatasan akses ketat antar admin, guru wali kelas, dan kepala sekolah.

---

### 🌐 Mode Deployment: Online vs Offline (LAN Intranet)

| Parameter | Mode Online (Cloud / VPS) | Mode Offline (LAN / Intranet Sekolah) |
|-----------|---------------------------|----------------------------------------|
| **Kebutuhan Internet** | Wajib ada koneksi internet di server & Kiosk | **Tanpa internet** (100% lokal jaringan LAN) |
| **Nilai `APP_URL`** | `https://presensi.sekolah.sch.id` | `http://192.168.1.100:8000` (IP Server Lokal) |
| **Akses Guru & Wali** | Bisa diakses dari mana saja (rumah/hp) | Hanya saat terhubung WiFi/LAN Sekolah |
| **Perangkat RFID (ESP32)**| Mengirim POST via internet ke domain public | Mengirim POST ke IP lokal server sekolah |
| **Keunggulan** | Fleksibel, monitoring dari luar sekolah | Bebas biaya hosting, tahan jika internet mati |

---

### Variabel Environment Penting (`.env`)

```env
APP_NAME="PRESENSI RTH NEXUS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://presensi.sekolah.sch.id   # (Atau http://192.168.x.x:8000 untuk Offline LAN)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presensi_rfid
DB_USERNAME=presensi_user
DB_PASSWORD=strong_password_here

CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp   # (opsional, untuk notifikasi email)
```

### Docker Production
```yaml
# docker-compose.override.yml (production)
services:
  app:
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      DB_PASSWORD: "ganti_dengan_password_kuat"
```

```bash
# Deploy ulang setelah update kode
docker-compose down
git pull origin main
docker-compose up -d --build
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
```

### Nginx Reverse Proxy dengan SSL Let's Encrypt (Mode Online)
```nginx
server {
    listen 80;
    server_name presensi.sekolah.sch.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name presensi.sekolah.sch.id;

    ssl_certificate /etc/letsencrypt/live/presensi.sekolah.sch.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/presensi.sekolah.sch.id/privkey.pem;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}
```

---

## 🧪 Testing

### Menjalankan Test Suite
```bash
# Via Docker
docker-compose exec -T -e SESSION_DRIVER=array -e CACHE_STORE=array app php artisan test

# Via lokal
php artisan test

# Dengan output detail
php artisan test --verbose
```

### Status Test Suite
```
Tests:    101 passed (241 assertions)
Duration: ~13 detik
```

### Coverage Area Test
- **Autentikasi** — login, logout, akses halaman terproteksi
- **RBAC** — validasi hak akses per peran
- **Siswa** — CRUD, import, export, soft delete
- **Guru** — CRUD, import, export
- **Kelas** — CRUD, export Excel/PDF
- **Presensi** — pencatatan manual, validasi status
- **Laporan** — generate PDF & Excel, rekap
- **Device** — token generation, regenerasi
- **API** — RFID scan, rekap endpoint, health check
- **Pengaturan** — semua tab sekolah, hari efektif, rate limit

---

## 🗄 Arsitektur Database

### Tabel Utama

```
academic_years          → Tahun ajaran (aktif/arsip)
classes                 → Data kelas (nama, wali kelas)
students                → Data siswa (RFID, foto, soft delete)
teachers                → Data guru (RFID, foto, multi-kelas, soft delete)
attendances             → Rekam presensi (7 status)
attendance_settings     → Konfigurasi jam masuk/pulang/terlambat
devices                 → Perangkat reader RFID (token keamanan)
rfid_logs               → Log raw scan RFID (audit trail)
school_settings         → Key-value store pengaturan sekolah
api_integrations        → API Key pihak ketiga
activity_logs           → Audit trail aksi admin
holidays                → Kalender hari libur nasional/sekolah
users                   → Akun pengguna sistem
roles / permissions     → Spatie RBAC tables
```

### Model Relasi Utama
```
Student  →  belongsTo  SchoolClass
Student  →  hasMany    Attendance
Teacher  →  hasMany    SchoolClass (wali)
Device   →  hasMany    RfidLog
User     →  hasMany    Role (via Spatie)
```

---

## 📁 Struktur Direktori Penting

```
presensi-rfid/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                  ← Endpoint RFID & Integrasi
│   │   │   ├── AttendanceController  ← Input manual
│   │   │   ├── DashboardController   ← Dashboard & Guide
│   │   │   ├── ReportController      ← Laporan, Export, Leaderboard
│   │   │   ├── SchoolSettingController ← Pengaturan sekolah
│   │   │   └── StudentController     ← CRUD + Import/Export
│   │   └── Middleware/
│   ├── Models/
│   │   ├── SchoolSetting.php         ← Key-value + Cache
│   │   ├── Student.php               ← Soft delete + scopes
│   │   └── Attendance.php            ← 7 status kehadiran
│   └── Exports/                      ← Excel export classes
├── database/
│   ├── migrations/                   ← 22 migrasi database
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolePermissionSeeder.php  ← 3 peran (admin, guru, kepsek)
│       └── DemoDataSeeder.php        ← 10 siswa + L/P + agama + kelas + absensi demo
├── resources/views/
│   ├── dashboard.blade.php           ← Dashboard utama
│   ├── guide.blade.php               ← Panduan pengguna built-in
│   ├── kiosk/scan.blade.php          ← Kiosk RFID (tanpa auth)
│   └── reports/
│       ├── public_leaderboard.blade.php ← TV Leaderboard (tanpa auth)
│       └── leaderboard.blade.php        ← Leaderboard admin
├── routes/
│   ├── web.php                       ← Routes web + RBAC middleware
│   └── api.php                       ← API RFID + Integrasi + Health
├── deploy.ps1                        ← Script deploy Windows
├── deploy.sh                         ← Script deploy Linux/macOS
├── Dockerfile                        ← Container image
└── docker-compose.yml                ← Stack: app + MySQL + Redis
```

---

## 🔒 Keamanan

- **Autentikasi** berbasis session Laravel (CSRF protected)
- **API RFID** diamankan dengan `X-Device-Token` per device
- **API Integrasi** diamankan dengan `X-API-Key` unik per mitra
- **Rate Limiting** per IP (configurable, default 60 req/menit)
- **Soft Delete** untuk data siswa & guru (tidak pernah hilang permanen)
- **Activity Log** semua aksi admin tersimpan di database
- **Middleware RBAC** di setiap route group
- File upload (logo, foto, wallpaper) disimpan di `storage/app/public`

---

## 🤝 Kontribusi

1. Fork repositori
2. Buat branch fitur: `git checkout -b feature/nama-fitur`
3. Commit dengan pesan deskriptif: `git commit -m "add: fitur X"`
4. Push: `git push origin feature/nama-fitur`
5. Buat Pull Request

### Standar Kode
- PHP: ikuti PSR-12 (gunakan `./vendor/bin/pint` untuk format otomatis)
- Blade: satu komponen per file, gunakan `x-` prefix
- Test: setiap fitur baru wajib disertai test

---

## 📄 Lisensi

MIT License — bebas digunakan, dimodifikasi, dan didistribusikan dengan mencantumkan atribusi.

---

## 🆘 Troubleshooting

### Container tidak mau start
```bash
docker-compose logs app     # cek error PHP/Laravel
docker-compose logs db      # cek error MySQL
docker-compose down -v      # reset total (hati-hati: data hilang)
docker-compose up -d
```

### Error "permission denied" pada storage
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage
```

### RFID tidak terbaca
1. Cek `X-Device-Token` di firmware sudah sesuai dengan token di panel **Devices**
2. Cek device **is_active = true** di panel admin
3. Cek endpoint: `POST http://[server]:8000/api/rfid/scan`
4. Cek log: `docker-compose exec app tail -f storage/logs/laravel.log`

### Foto siswa tidak muncul di leaderboard
1. Jalankan: `docker-compose exec app php artisan storage:link`
2. Pastikan kolom `foto` di tabel `students` berisi path yang valid
3. File foto harus ada di `storage/app/public/`

### Database error setelah update
```bash
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

---

<div align="center">

**PRESENSI RTH NEXUS** — Dibuat dengan ❤️ untuk kemajuan pendidikan Indonesia

*Laravel 13 · PHP 8.3 · Docker · Redis · MySQL 8*

</div>
