"""
============================================================
  AKSARA - Performance Testing (Alur Sederhana)
  Fokus: Login -> Dashboard -> Absensi
============================================================

Cara Menjalankan:
  locust -f locustfile_simple.py --host=http://localhost:8000

  Buka http://localhost:8089, isi jumlah user & ramp-up, Start swarming.
"""

import re
import random
from locust import HttpUser, task, between
from locust.exception import StopUser

# ============================================================
# KONFIGURASI AKUN (sesuaikan dengan database Anda)
# ============================================================
GURU_CREDENTIALS = [
    {"login": "pyroarchon@gmail.com", "password": "12345678"},
]


def extract_csrf_token(html_text):
    match = re.search(r'<meta name="csrf-token" content="([^"]+)"', html_text)
    if match:
        return match.group(1)
    match = re.search(r'name="_token"[^>]*value="([^"]+)"', html_text)
    if match:
        return match.group(1)
    return ""


class GuruFlow(HttpUser):
    """
    Simulasi 1 alur pengguna Guru:
    1. Buka halaman login
    2. Login
    3. Buka dashboard
    4. Buka halaman absensi
    """
    wait_time = between(2, 5)

    def on_start(self):
        self.csrf_token = None
        if not self.login():
            raise StopUser()

    def login(self):
        with self.client.get("/", catch_response=True, name="1. GET /login") as r:
            if r.status_code != 200:
                r.failure(f"Gagal buka halaman login: {r.status_code}")
                return False
            csrf_token = extract_csrf_token(r.text)

        cred = random.choice(GURU_CREDENTIALS)
        payload = {
            "_token": csrf_token,
            "login": cred["login"],
            "password": cred["password"],
        }

        with self.client.post(
            "/login",
            data=payload,
            catch_response=True,
            allow_redirects=True,
            name="2. POST /login"
        ) as r:
            if "Email/Username atau password salah" in r.text:
                r.failure("Login gagal - cek kredensial")
                return False
            if r.status_code == 200:
                self.csrf_token = extract_csrf_token(r.text) or csrf_token
                r.success()
                return True
            r.failure(f"Login gagal, status: {r.status_code}")
            return False

    @task(1)
    def dashboard(self):
        with self.client.get("/guru/dashboard", catch_response=True, name="3. GET Dashboard") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"Status: {r.status_code}")

    @task(2)
    def absensi(self):
        with self.client.get("/guru/absensi", catch_response=True, name="4. GET Absensi") as r:
            if r.status_code == 200:
                r.success()
            else:
                r.failure(f"Status: {r.status_code}")

    @task(2)
    def kelola_absensi(self):
        """
        Alur kelola absensi:
        5. GET halaman absensi -> ambil kelas_id
        6. GET halaman kelola absensi kelas tsb -> ambil csrf_token & daftar siswa
        7. POST simpan absensi
        """
        with self.client.get("/guru/absensi", catch_response=True, name="5. GET Absensi (untuk kelola)") as r:
            if r.status_code != 200:
                r.failure(f"GET list gagal: {r.status_code}")
                return
            match = re.search(r'/guru/absensi/(\d+)/pilih-bulan', r.text)
            if not match:
                return
            kelas_id = match.group(1)

        url_kelola = f"/guru/absensi/{kelas_id}/kelola/juni/30"
        with self.client.get(url_kelola, catch_response=True, name="6. GET Kelola Absensi") as r:
            if r.status_code != 200:
                r.failure(f"GET kelola gagal: {r.status_code}")
                return
            csrf_token = extract_csrf_token(r.text)
            student_ids = re.findall(r'name="status\[(\d+)\]"', r.text)

        if not student_ids:
            return

        payload = {"_token": csrf_token}
        for s_id in student_ids:
            payload[f"status[{s_id}]"] = random.choice(['H', 'S', 'I', 'A'])
            payload[f"keterangan[{s_id}]"] = "Uji otomatis"

        with self.client.post(url_kelola, data=payload, catch_response=True, name="7. POST Simpan Absensi") as r:
            if r.status_code in [200, 302]:
                r.success()
            else:
                r.failure(f"POST simpan absensi gagal: {r.status_code}")