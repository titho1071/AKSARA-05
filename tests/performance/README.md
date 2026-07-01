# 🚀 Panduan Performance Testing AKSARA dengan Locust

## Prasyarat
- Python 3.8+ sudah terinstall
- Aplikasi AKSARA berjalan di `http://localhost:8000` (atau port lainnya)

---

## 📦 Langkah 1 — Install Locust

Buka terminal, masuk ke folder ini:

```bash
cd tests/performance
pip install -r requirements.txt
```

---

## ⚙️ Langkah 2 — Konfigurasi Akun

Buka file `locustfile.py` dan sesuaikan bagian **KONFIGURASI AKUN** dengan username & password asli di database Anda:

```python
ADMIN_CREDENTIALS = [
    {"login": "admin@sekolah.com", "password": "password"},
]

GURU_CREDENTIALS = [
    {"login": "guru@sekolah.com", "password": "password"},
]

ORANGTUA_CREDENTIALS = [
    {"login": "ortu@sekolah.com", "password": "password"},
]
```

> **Catatan:** Username bisa berupa email atau NIP, tergantung implementasi login di AuthController.

---

## ▶️ Langkah 3 — Jalankan Locust

### Opsi A: Dengan Web UI (Direkomendasikan untuk pemula)
```bash
locust -f locustfile.py --host=http://localhost:8000
```
Kemudian buka browser ke: **http://localhost:8089**

Isi formulir:
- **Number of users (peak):** `50` (jumlah user simultan)
- **Spawn rate:** `5` (berapa user yang ditambahkan per detik)
- **Host:** `http://localhost:8000`

### Opsi B: Headless (Tanpa UI, untuk CI/CD atau laporan otomatis)
```bash
# Load Test: 50 user, spawn 5/detik, durasi 60 detik
locust -f locustfile.py --host=http://localhost:8000 --headless -u 50 -r 5 -t 60s

# Simpan hasil ke CSV
locust -f locustfile.py --host=http://localhost:8000 --headless -u 50 -r 5 -t 60s --csv=hasil_test
```

---

## 🎯 Fitur dan Metode yang Diuji (CRUD Lengkap)

Pengujian disimulasikan sebagai alur lengkap **CRUD (Create, Read, Update, Delete)** untuk memastikan performa database dan server terjaga saat proses tulis, edit, dan hapus data:

1. **Pengumuman (Admin)**
   - **GET**: Membuka list, detail, form tambah, dan form edit pengumuman.
   - **POST**: Membuat pengumuman baru (`POST /api/pengumuman`).
   - **PUT**: Memperbarui judul & isi pengumuman (`PUT /api/pengumuman/{id}`).
   - **DELETE**: Menghapus pengumuman (`DELETE /api/pengumuman/{id}`) untuk membersihkan DB lokal Anda.

2. **Dokumentasi Kegiatan (Guru)**
   - **GET**: Membuka list, detail, form tambah, dan form edit dokumentasi.
   - **POST**: Mengunggah dokumentasi kegiatan dengan file foto tiruan (`POST /guru/dokumentasi`).
   - **PUT**: Memperbarui judul & deskripsi dokumentasi (`PUT /guru/dokumentasi/{id}`).
   - **DELETE**: Menghapus dokumentasi beserta file fotonya (`DELETE /guru/dokumentasi/{id}`).

3. **Jadwal Pelajaran (Admin)**
   - **GET**: Mengambil relasi kelas/jam/mapel lewat API, serta melihat detail jadwal.
   - **POST**: Membuat slot jadwal pelajaran baru (`POST /api/jadwal-pelajaran`).
   - **PUT**: Mengubah jadwal pelajaran (`PUT /api/jadwal-pelajaran/{id}`).
   - **DELETE**: Menghapus jadwal pelajaran (`DELETE /api/jadwal-pelajaran/{id}`).

4. **Absensi (Guru)**
   - **GET**: Membuka kelas dan formulir kelola absensi.
   - **POST/PUT**: Menyimpan absensi siswa dengan status kehadiran acak (`POST /guru/absensi/{id}/kelola/...` menggunakan metode `updateOrCreate` Laravel).

---

## 🎯 Skenario Pengujian yang Disarankan

### 1. Load Test (Beban Normal)
```bash
locust -f locustfile.py --host=http://localhost:8000 --headless -u 30 -r 3 -t 120s --csv=load_test
```
- 30 pengguna simultan
- Bertujuan memastikan aplikasi berjalan normal di kondisi biasa

### 2. Stress Test (Beban Maksimal)
```bash
locust -f locustfile.py --host=http://localhost:8000 --headless -u 100 -r 10 -t 120s --csv=stress_test
```
- 100 pengguna simultan
- Menemukan batas kemampuan sistem

### 3. Spike Test (Lonjakan Tiba-tiba)
```bash
# Jalankan dengan UI, lalu naikkan user secara drastis dari 10 ke 200
locust -f locustfile.py --host=http://localhost:8000
```
- Simulasi saat banyak orang login serentak (misal awal semester)

---

## 📊 Membaca Hasil

| Metrik          | Baik             | Cukup            | Buruk              |
|-----------------|------------------|------------------|--------------------|
| Response Time   | < 500ms          | 500ms - 2s       | > 2s               |
| Failure Rate    | < 1%             | 1% - 5%          | > 5%               |
| RPS             | Stabil           | Sedikit fluktuasi| Drop drastis       |

**Kolom penting di Locust UI:**
- `Requests/s` — Throughput sistem
- `50th/95th percentile` — Response time mayoritas user
- `Failures/s` — Error rate

---

## 📁 Struktur File
```
tests/performance/
├── locustfile.py     # File utama Locust
├── requirements.txt  # Dependensi Python
└── README.md         # Panduan ini
```
