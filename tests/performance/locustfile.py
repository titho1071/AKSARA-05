"""
============================================================
  AKSARA - Performance Testing (Fokus Fitur Utama - CRUD Lengkap)
  Pengujian Non-Fungsional: GET, POST, PUT, DELETE
============================================================

Cara Menjalankan:
  1. Install locust:       pip install locust
  2. Jalankan:             locust -f locustfile.py --host=http://localhost:8000
  3. Buka browser:         http://localhost:8089
"""

import re
import random
from locust import HttpUser, TaskSet, task, between
from locust.exception import StopUser
import os

# Taruh di scope module, di-load sekali saat locust start
TEST_IMAGE_PATH = os.path.join(os.path.dirname(__file__), "test.png")
with open(TEST_IMAGE_PATH, "rb") as f:
    TEST_IMAGE_BYTES = f.read()

# ============================================================
# KONFIGURASI AKUN (Silakan sesuaikan dengan database Anda)
# ============================================================
ADMIN_CREDENTIALS = [
    {"login": "admin@gmail.com", "password": "admin123"},
]

GURU_CREDENTIALS = [
    {"login": "pyroarchon@gmail.com", "password": "12345678"},
]

ORANGTUA_CREDENTIALS = [
    {"login": "alice@gmail.com", "password": "12345678"},
]

# ============================================================
# HELPER: Login & CSRF Token Extraction
# ============================================================
def extract_csrf_token(html_text):
    """Mengekstrak CSRF token dari halaman Laravel."""
    match = re.search(r'<meta name="csrf-token" content="([^"]+)"', html_text)
    if match:
        return match.group(1)
    match = re.search(r'name="_token"[^>]*value="([^"]+)"', html_text)
    if match:
        return match.group(1)
    match = re.search(r'value="([^"]+)"[^>]*name="_token"', html_text)
    if match:
        return match.group(1)
    return ""


def do_login(user, credentials):
    """
    Melakukan login ke aplikasi AKSARA.
    `user` adalah instance TaskSet (self), supaya csrf_token bisa disimpan
    ke user.csrf_token dan dipakai lagi nanti (misalnya saat logout).
    """
    with user.client.get("/", catch_response=True, name="GET /login") as response:
        if response.status_code != 200:
            response.failure(f"Gagal memuat halaman login: {response.status_code}")
            return False
        csrf_token = extract_csrf_token(response.text)

    cred = random.choice(credentials)
    payload = {
        "_token": csrf_token,
        "login": cred["login"],
        "password": cred["password"],
    }

    with user.client.post(
        "/login",
        data=payload,
        catch_response=True,
        allow_redirects=True,
        name="POST /login"
    ) as response:
        if "Email/Username atau password salah" in response.text:
            response.failure("Login gagal - Cek kredensial di locustfile.py")
            return False
        if "dashboard" in response.url or response.status_code == 200:
            # Simpan token terbaru (dari halaman dashboard hasil redirect)
            # supaya bisa dipakai lagi untuk request POST/logout berikutnya.
            latest_token = extract_csrf_token(response.text)
            user.csrf_token = latest_token or csrf_token
            return True
        response.failure(f"Login gagal, status: {response.status_code}")
        return False


def do_logout(user):
    """Melakukan logout dengan menyertakan CSRF token yang tersimpan."""
    token = getattr(user, "csrf_token", "") or ""
    with user.client.post(
        "/logout",
        data={"_token": token},
        catch_response=True,
        name="POST /logout"
    ) as r:
        if r.status_code in [200, 302]:
            r.success()
        else:
            r.failure(f"Logout gagal: {r.status_code} - {r.text[:300]}")


# ============================================================
# SKENARIO ADMIN (Fokus Fitur Utama - CRUD)
# ============================================================
class AdminTasks(TaskSet):
    def on_start(self):
        self.csrf_token = None
        success = do_login(self, ADMIN_CREDENTIALS)
        if not success:
            raise StopUser()

    def on_stop(self):
        do_logout(self)

    @task(3)
    def dashboard(self):
        with self.client.get("/admin/dashboard", catch_response=True, name="[Admin] Dashboard (GET)") as r:
            if r.status_code in [200, 302]:
                self.csrf_token = extract_csrf_token(r.text) or self.csrf_token
                r.success()
            else:
                r.failure(f"Status: {r.status_code}")

    @task(3)
    def absensi_list(self):
        with self.client.get("/admin/absensi", catch_response=True, name="[Admin] Halaman Absensi (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")

    @task(3)
    def pengumuman_list(self):
        with self.client.get("/admin/pengumuman", catch_response=True, name="[Admin] Halaman Pengumuman (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")

    @task(2)
    def pengumuman_crud(self):
        """CRUD Lengkap Pengumuman (GET, POST, PUT, DELETE) dalam satu alur."""
        # 1. GET Halaman Create
        with self.client.get("/admin/pengumuman/create", catch_response=True, name="[Admin] Form Tambah Pengumuman (GET)") as r:
            if r.status_code != 200:
                r.failure(f"GET form gagal: {r.status_code}")
                return
            csrf_token = extract_csrf_token(r.text)
            self.csrf_token = csrf_token or self.csrf_token

        # 2. POST (Create)
        payload_create = {
            "_token": csrf_token,
            "judul": f"Pengumuman Uji Locust {random.randint(10000, 99999)}",
            "deskripsi": "Deskripsi pengumuman CRUD otomatis yang dibuat selama load testing.",
            "kelas_id": "",
            "tanggal_mulai": "2026-06-30",
            "tanggal_selesai": "2026-07-05",
        }

        headers = {"X-Requested-With": "XMLHttpRequest"}
        id_pengumuman = None

        with self.client.post("/api/pengumuman", data=payload_create, headers=headers, catch_response=True, name="[Admin] Tambah Pengumuman (POST)") as r:
            if r.status_code in [200, 201]:
                res_data = r.json()
                if res_data.get("success") and "data" in res_data:
                    id_pengumuman = res_data["data"].get("id_pengumuman")
                    r.success()
                else:
                    r.failure("Format JSON respons tidak valid atau sukses bernilai false")
                    return
            else:
                r.failure(f"POST gagal: {r.status_code}")
                return

        if not id_pengumuman:
            return

        # 3. GET Detail & GET Edit Page
        self.client.get(f"/admin/pengumuman/{id_pengumuman}", name="[Admin] Detail Pengumuman (GET)")
        self.client.get(f"/admin/pengumuman/{id_pengumuman}/edit", name="[Admin] Form Edit Pengumuman (GET)")

        # 4. PUT (Update)
        payload_update = {
            "_token": csrf_token,
            "_method": "PUT",
            "judul": f"Pengumuman Uji Locust [UPDATED] {random.randint(1000, 9999)}",
            "deskripsi": "Deskripsi pengumuman CRUD otomatis yang sudah diperbarui.",
            "kelas_id": "",
            "tanggal_mulai": "2026-06-30",
            "tanggal_selesai": "2026-07-06",
        }

        with self.client.post(f"/api/pengumuman/{id_pengumuman}", data=payload_update, headers=headers, catch_response=True, name="[Admin] Perbarui Pengumuman (PUT)") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"PUT gagal: {r.status_code}")

        # 5. DELETE (Delete)
        payload_delete = {
            "_token": csrf_token,
            "_method": "DELETE"
        }
        with self.client.post(f"/api/pengumuman/{id_pengumuman}", data=payload_delete, headers=headers, catch_response=True, name="[Admin] Hapus Pengumuman (DELETE)") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"DELETE gagal: {r.status_code}")

    @task(2)
    def jadwal_pelajaran_crud(self):
        """CRUD Lengkap Jadwal Pelajaran (GET, POST, PUT, DELETE) melalui API."""
        # 1. GET Kelas
        with self.client.get("/api/kelas", catch_response=True, name="[Admin] Get API Kelas (GET)") as r:
            if r.status_code != 200:
                r.failure("Gagal mengambil list kelas")
                return
            kelas_list = r.json()
            if not kelas_list:
                return
            kelas_id = random.choice(kelas_list).get("id_kelas")

        # 2. GET Jam Pelajaran
        with self.client.get("/api/jam-pelajaran", catch_response=True, name="[Admin] Get API Jam Pelajaran (GET)") as r:
            if r.status_code != 200:
                r.failure("Gagal mengambil list jam pelajaran")
                return
            jam_data = r.json().get("data", [])
            if not jam_data:
                return
            jam_id = random.choice(jam_data).get("id_jam")

        # 3. GET Mata Pelajaran
        with self.client.get("/api/mata-pelajaran", catch_response=True, name="[Admin] Get API Mata Pelajaran (GET)") as r:
            if r.status_code != 200:
                r.failure("Gagal mengambil list mata pelajaran")
                return
            mapel_data = r.json().get("data", [])
            if not mapel_data:
                return
            mapel_id = random.choice(mapel_data).get("id_mapel")

        # Dapatkan CSRF Token dari dashboard admin
        with self.client.get("/admin/dashboard", catch_response=True, name="[Admin] Get CSRF for Jadwal") as r:
            csrf_token = extract_csrf_token(r.text)
            self.csrf_token = csrf_token or self.csrf_token

        headers = {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": csrf_token
        }

        # 4. POST (Create)
        hari = random.choice(["Senin", "Selasa", "Rabu", "Kamis", "Jumat"])
        payload_create = {
            "hari": hari,
            "jam_id": jam_id,
            "kelas_id": kelas_id,
            "id_mapel": mapel_id,
            "nama_kegiatan": f"KBM Uji Locust {random.randint(100, 999)}",
        }

        id_jadwal = None
        with self.client.post("/api/jadwal-pelajaran", json=payload_create, headers=headers, catch_response=True, name="[Admin] Tambah Jadwal (POST)") as r:
            if r.status_code in [200, 201]:
                res_data = r.json()
                id_jadwal = res_data.get("data", {}).get("id_jadwal")
                r.success()
            elif r.status_code == 422:
                # Slot sudah terisi, wajar terjadi dalam load test, tetap sukses
                r.success()
                return
            else:
                r.failure(f"POST Jadwal gagal: {r.status_code} - {r.text}")
                return

        if not id_jadwal:
            return

        # 5. GET Detail Jadwal
        self.client.get(f"/api/jadwal-pelajaran/{id_jadwal}", name="[Admin] Detail Jadwal (GET)")

        # 6. PUT (Update)
        payload_update = {
            "hari": hari,
            "jam_id": jam_id,
            "kelas_id": kelas_id,
            "id_mapel": mapel_id,
            "nama_kegiatan": f"KBM Uji Locust [UPDATED] {random.randint(100, 999)}",
        }
        with self.client.put(f"/api/jadwal-pelajaran/{id_jadwal}", json=payload_update, headers=headers, catch_response=True, name="[Admin] Perbarui Jadwal (PUT)") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"PUT Jadwal gagal: {r.status_code}")

        # 7. DELETE (Delete)
        with self.client.delete(f"/api/jadwal-pelajaran/{id_jadwal}", headers=headers, catch_response=True, name="[Admin] Hapus Jadwal (DELETE)") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"DELETE Jadwal gagal: {r.status_code}")


# ============================================================
# SKENARIO GURU (Fokus Fitur Utama - CRUD)
# ============================================================
class GuruTasks(TaskSet):
    def on_start(self):
        self.csrf_token = None
        success = do_login(self, GURU_CREDENTIALS)
        if not success:
            raise StopUser()

    def on_stop(self):
        do_logout(self)

    @task(3)
    def dashboard(self):
        with self.client.get("/guru/dashboard", catch_response=True, name="[Guru] Dashboard (GET)") as r:
            if r.status_code == 200:
                self.csrf_token = extract_csrf_token(r.text) or self.csrf_token
                r.success()
            else:
                r.failure(f"Status: {r.status_code}")

    @task(3)
    def absensi_flow(self):
        """Simulasi mengisi absensi (GET & POST/PUT)."""
        with self.client.get("/guru/absensi", catch_response=True, name="[Guru] Halaman Daftar Absensi (GET)") as r:
            if r.status_code != 200:
                r.failure(f"GET list gagal: {r.status_code}")
                return
            match = re.search(r'/guru/absensi/(\d+)/pilih-bulan', r.text)
            if not match:
                return
            kelas_id = match.group(1)

        url_kelola = f"/guru/absensi/{kelas_id}/kelola/juni/30"
        with self.client.get(url_kelola, catch_response=True, name="[Guru] Halaman Kelola Absensi (GET)") as r:
            if r.status_code != 200:
                r.failure(f"GET kelola gagal: {r.status_code}")
                return
            csrf_token = extract_csrf_token(r.text)
            self.csrf_token = csrf_token or self.csrf_token
            student_ids = re.findall(r'name="status\[(\d+)\]"', r.text)

        if not student_ids:
            return

        payload = {
            "_token": csrf_token,
        }
        for s_id in student_ids:
            payload[f"status[{s_id}]"] = random.choice(['H', 'S', 'I', 'A'])
            payload[f"keterangan[{s_id}]"] = "Uji otomatis"

        url_simpan = f"/guru/absensi/{kelas_id}/kelola/juni/30"
        with self.client.post(url_simpan, data=payload, catch_response=True, name="[Guru] Simpan Absensi (POST/PUT)") as r:
            if r.status_code in [200, 302]:
                r.success()
            else:
                r.failure(f"POST simpan absensi gagal: {r.status_code}")

    @task(2)
    def dokumentasi_crud(self):
        """CRUD Lengkap Dokumentasi Kegiatan (GET, POST, PUT, DELETE) dalam satu alur."""
        # 1. GET Halaman Create
        with self.client.get("/guru/dokumentasi/create", catch_response=True, name="[Guru] Form Tambah Dokumentasi (GET)") as r:
            if r.status_code != 200:
                r.failure(f"GET create form gagal: {r.status_code}")
                return
            csrf_token = extract_csrf_token(r.text)
            self.csrf_token = csrf_token or self.csrf_token

        # 2. POST (Create)
        payload_create = {
            "_token": csrf_token,
            "judul": f"Kegiatan Uji Locust {random.randint(1000, 9999)}",
            "deskripsi": "Deskripsi kegiatan load test Locust.",
            "tanggal": "2026-06-30",
            "kelas_id": "semua_kelas",
        }
        files = {
            "foto[]": ("test.png", TEST_IMAGE_BYTES, "image/png")
        }

        # Laravel web form upload
        id_kegiatan = None
        with self.client.post("/guru/dokumentasi", data=payload_create, files=files, allow_redirects=True, catch_response=True, name="[Guru] Tambah Dokumentasi (POST)") as r:
            if r.status_code == 200:
                # Scrape ID kegiatan terbaru dari tabel di halaman index hasil redirect
                match = re.search(r'/guru/dokumentasi/(\d+)/edit', r.text)
                if match:
                    id_kegiatan = match.group(1)
                    # Update token dari halaman hasil redirect, siapa tahu berubah
                    self.csrf_token = extract_csrf_token(r.text) or self.csrf_token
                    r.success()
                else:
                    errors = re.findall(r'<li>(.*?)</li>', r.text)
                    r.failure(f"ID tidak ditemukan. Validasi/errors: {errors[:5]}")
                    return
            else:
                r.failure(f"POST unggahan gagal: {r.status_code}")
                return

        if not id_kegiatan:
            return

        # 3. GET Detail & GET Edit Page
        self.client.get(f"/guru/dokumentasi/{id_kegiatan}", name="[Guru] Detail Dokumentasi (GET)")
        with self.client.get(f"/guru/dokumentasi/{id_kegiatan}/edit", catch_response=True, name="[Guru] Form Edit Dokumentasi (GET)") as r:
            if r.status_code == 200:
                # Ambil token terbaru dari form edit, ini yang paling aman dipakai
                # untuk PUT/DELETE berikutnya.
                edit_token = extract_csrf_token(r.text)
                if edit_token:
                    csrf_token = edit_token
                    self.csrf_token = edit_token
                r.success()
            else:
                r.failure(f"GET edit form gagal: {r.status_code}")

        # 4. PUT (Update) menggunakan Form Spoofing method
        payload_update = {
            "_token": csrf_token,
            "_method": "PUT",
            "judul": f"Kegiatan Uji Locust [UPDATED] {random.randint(1000, 9999)}",
            "deskripsi": "Deskripsi kegiatan load test Locust sudah diperbarui.",
            "tanggal": "2026-06-30",
            "kelas_id": "semua_kelas",
        }
        with self.client.post(f"/guru/dokumentasi/{id_kegiatan}", data=payload_update, catch_response=True, name="[Guru] Perbarui Dokumentasi (PUT)") as r:
            if r.status_code in [200, 302]:
                r.success()
            else:
                r.failure(f"PUT perbarui gagal: {r.status_code} - {r.text[:300]}")

        # 5. DELETE (Delete) menggunakan Form Spoofing method
        payload_delete = {
            "_token": csrf_token,
            "_method": "DELETE"
        }
        with self.client.post(f"/guru/dokumentasi/{id_kegiatan}", data=payload_delete, catch_response=True, name="[Guru] Hapus Dokumentasi (DELETE)") as r:
            if r.status_code in [200, 302]:
                r.success()
            else:
                r.failure(f"DELETE gagal: {r.status_code} - {r.text[:300]}")


# ============================================================
# SKENARIO ORANG TUA (Fokus Fitur Utama - Hanya GET)
# ============================================================
class OrangTuaTasks(TaskSet):
    def on_start(self):
        self.csrf_token = None
        success = do_login(self, ORANGTUA_CREDENTIALS)
        if not success:
            raise StopUser()

    def on_stop(self):
        do_logout(self)

    @task(3)
    def dashboard(self):
        with self.client.get("/orangtua/dashboard", catch_response=True, name="[OrangTua] Dashboard (GET)") as r:
            if r.status_code == 200:
                self.csrf_token = extract_csrf_token(r.text) or self.csrf_token
                r.success()
            else:
                r.failure(f"Status: {r.status_code}")

    @task(5)
    def absensi(self):
        with self.client.get("/orangtua/absensi", catch_response=True, name="[OrangTua] Halaman Absensi Anak (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")

    @task(4)
    def dokumentasi(self):
        with self.client.get("/orangtua/dokumentasi", catch_response=True, name="[OrangTua] Halaman Dokumentasi Kegiatan (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")

    @task(4)
    def pengumuman(self):
        with self.client.get("/orangtua/pengumuman", catch_response=True, name="[OrangTua] Halaman Pengumuman Sekolah (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")

    @task(3)
    def jadwal(self):
        with self.client.get("/orangtua/jadwal", catch_response=True, name="[OrangTua] Halaman Jadwal Pelajaran Anak (GET)") as r:
            r.success() if r.status_code == 200 else r.failure(f"Status: {r.status_code}")


# ============================================================
# USER CLASSES & DISTRIBUSI BEBAN
# ============================================================
class AdminUser(HttpUser):
    tasks = [AdminTasks]
    wait_time = between(3, 7)
    weight = 1


class GuruUser(HttpUser):
    tasks = [GuruTasks]
    wait_time = between(2, 5)
    weight = 3


class OrangTuaUser(HttpUser):
    tasks = [OrangTuaTasks]
    wait_time = between(2, 5)
    weight = 6