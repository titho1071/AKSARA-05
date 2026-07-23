<?php

namespace Tests\Feature\Guru;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuruTest extends TestCase
{
    use DatabaseTransactions;

    // ============================================================
    // HELPER
    // ============================================================
    private function buatGuru(): array
    {
        $roleId  = \DB::table('roles')->where('nama_role', 'guru')->value('id_role');
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');

        $user = User::factory()->create(['role_id' => $roleId]);

        $guruId = \DB::table('guru')->insertGetId([
            'user_id' => $user->id,
            'nama'    => 'Guru Test ' . rand(100, 999),
            'status'  => 'aktif',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        return compact('user', 'guruId', 'kelas', 'tapelId');
    }

    private function buatSiswa(int $kelasId): Siswa
    {
        return Siswa::create([
            'nama'          => 'Siswa Test ' . rand(100, 999),
            'nis'           => 'NIS' . rand(10000, 99999),
            'nisn'          => (string) rand(1000000000, 9999999999),
            'jenis_kelamin' => 'L',
            'kelas_id'      => $kelasId,
            'status'        => 'aktif',
        ]);
    }

    // ============================================================
    // JADWAL GURU
    // ============================================================
    public function test_guru_dapat_melihat_halaman_jadwal()
    {
        ['user' => $user] = $this->buatGuru();

        $response = $this->actingAs($user)->get('/guru/jadwal');
        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_jadwal_guru()
    {
        $response = $this->get('/guru/jadwal');
        $response->assertRedirect('/');
    }

    // ============================================================
    // WALI KELAS — DATA SISWA
    // ============================================================
    public function test_guru_dapat_melihat_daftar_siswa_kelasnya()
    {
        ['user' => $user, 'kelas' => $kelas] = $this->buatGuru();
        $this->buatSiswa($kelas->id_kelas);

        $response = $this->actingAs($user)->get('/guru/siswa');
        $response->assertStatus(200);
    }

    public function test_guru_dapat_melihat_form_edit_siswa_kelasnya()
    {
        ['user' => $user, 'kelas' => $kelas] = $this->buatGuru();
        $siswa = $this->buatSiswa($kelas->id_kelas);

        $response = $this->actingAs($user)
            ->get("/guru/siswa/{$siswa->id_siswa}/edit");

        $response->assertStatus(200);
    }

    public function test_guru_dapat_update_data_siswa_kelasnya()
    {
        ['user' => $user, 'kelas' => $kelas] = $this->buatGuru();
        $siswa = $this->buatSiswa($kelas->id_kelas);

        $response = $this->actingAs($user)
            ->put("/guru/siswa/{$siswa->id_siswa}", [
                'nama'          => 'Nama Baru',
                'nis'           => $siswa->nis,
                'nisn'          => $siswa->nisn,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-05-01',
                'alamat'        => 'Jl. Test No. 1',
                'orang_tua_id'  => null,
            ]);

        $response->assertRedirect(route('guru.siswa.index'));
        $this->assertDatabaseHas('siswa', [
            'id_siswa' => $siswa->id_siswa,
            'nama'     => 'Nama Baru',
        ]);
    }

    public function test_guru_tidak_bisa_edit_siswa_kelas_lain()
    {
        ['user' => $user]       = $this->buatGuru();
        ['kelas' => $kelasLain] = $this->buatGuru();

        $siswaLain = $this->buatSiswa($kelasLain->id_kelas);

        $response = $this->actingAs($user)
            ->get("/guru/siswa/{$siswaLain->id_siswa}/edit");

        $this->assertNotEquals(200, $response->getStatusCode());
    }

    public function test_guest_tidak_bisa_akses_data_siswa()
    {
        $response = $this->get('/guru/siswa');
        $response->assertRedirect('/');
    }

    // ============================================================
    // REKAP ABSENSI GURU
    // ============================================================
    public function test_guru_dapat_melihat_halaman_rekap_absensi()
    {
        ['user' => $user] = $this->buatGuru();

        $response = $this->actingAs($user)->get('/guru/absensi/rekap');
        $response->assertStatus(200);
    }

    // ============================================================
    // PENGUMUMAN GURU (READ)
    // ============================================================
    public function test_guru_dapat_melihat_halaman_pengumuman()
    {
        ['user' => $user] = $this->buatGuru();

        $response = $this->actingAs($user)->get('/guru/pengumuman');
        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_pengumuman_guru()
    {
        $response = $this->get('/guru/pengumuman');
        $response->assertRedirect('/');
    }

    // ============================================================
    // DOKUMENTASI GURU
    // ============================================================
    public function test_guru_dapat_melihat_daftar_dokumentasi()
    {
        ['user' => $user] = $this->buatGuru();

        $response = $this->actingAs($user)->get('/guru/dokumentasi');
        $response->assertStatus(200);
    }

    public function test_guru_dapat_melihat_detail_dokumentasi_miliknya()
    {
        ['user' => $user] = $this->buatGuru();

        $kegiatan = Kegiatan::create([
            'user_id'   => $user->id,
            'kelas_id'  => 'semua_kelas',
            'judul'     => 'Kegiatan Detail Test',
            'deskripsi' => 'Deskripsi.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user)
            ->get("/guru/dokumentasi/{$kegiatan->id_kegiatan}");

        $response->assertStatus(200);
    }

    public function test_guru_tidak_bisa_akses_detail_dokumentasi_guru_lain()
    {
        ['user' => $user1] = $this->buatGuru();
        ['user' => $user2] = $this->buatGuru();

        $kegiatan = Kegiatan::create([
            'user_id'   => $user1->id,
            'kelas_id'  => 'semua_kelas',
            'judul'     => 'Kegiatan Guru 1',
            'deskripsi' => 'Deskripsi.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user2)
            ->get("/guru/dokumentasi/{$kegiatan->id_kegiatan}");

        $response->assertStatus(403);
    }
}