# 🚀 Grafana k6 Load Testing — RTH NEXUS Presensi RFID

Direktori ini berisi skrip pengujian beban (*load testing*) menggunakan **Grafana k6** untuk menguji performa, batas kapasitas (*throughput*), serta ketahanan sistem **Presensi RFID RTH NEXUS** pada jam-jam sibuk.

---

## 🛠️ 1. Cara Instalasi Grafana k6

### Windows (via winget / choco / scoop):
```powershell
# Menggunakan winget (Windows Package Manager)
winget install k6 --source winget

# Atau menggunakan Chocolatey
choco install k6

# Atau download installer dari official website: https://k6.io/docs/get-started/installation/
```

### Linux (Ubuntu / Debian):
```bash
sudo gpg -k
sudo gpg --no-default-keyring --keyring /usr/share/keyrings/k6-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5AD17C747E3415A3642D57D77C6C491D6AC1D69
echo "deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main" | sudo tee /etc/apt/sources.list.d/k6.list
sudo apt-get update
sudo apt-get install k6
```

---

## ⚡ 2. Skrip Pengujian yang Tersedia

| Nama File Skrip | Deskripsi Pengujian | Skenario Beban |
| :--- | :--- | :--- |
| `rfid_kiosk_load_test.js` | Simulasi jam sibuk siswa tap kartu RFID di gerbang sekolah (`/api/rfid/scan`). | 50 VU → 200 VU → **500 Virtual Users (Peak)** |
| `api_integration_load_test.js` | Simulasi aplikasi pihak ketiga mengambil data rekap presensi (`/api/v1/*`) & Health Check (`/api/health`). | 20 VU → 100 Virtual Users + Rate Limit Test |

---

## 🏃 3. Cara Menjalankan Load Test

### A. Menjalankan Load Test Pemindai Kiosk RFID (`rfid_kiosk_load_test.js`)
```powershell
# Jalankan dengan default localhost:8000
k6 run k6/rfid_kiosk_load_test.js

# Jalankan dengan kustomisasi URL aplikasi & token device RFID
k6 run -e BASE_URL="http://localhost:8000" -e DEVICE_TOKEN="token-device-rahasia" k6/rfid_kiosk_load_test.js
```

### B. Menjalankan Load Test Integrasi API Eksternal (`api_integration_load_test.js`)
```powershell
k6 run -e BASE_URL="http://localhost:8000" -e API_KEY="key-integrasi-anda" k6/api_integration_load_test.js
```

---

## 📊 4. Indikator Performa Yang Diuji (Thresholds)

- **`http_req_duration (p95)`**: 95% dari seluruh request tap RFID kartu wajib direspons di bawah **200 milidetik (ms)**.
- **`http_req_failed`**: Tingkat kegagalan sistem (*Error Rate*) wajib di bawah **1%**.
- **`http_reqs`**: Mengukur total Request Per Second (RPS) yang mampu dilayani MySQL & Laravel.
