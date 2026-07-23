<?php

namespace Tests\Feature\OrangTua;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrangTuaTest extends TestCase
{
    use DatabaseTransactions;

    // ============================================================
    // HELPER
    // ============================================================
    private function buatOrangTua(): array
    {
        $roleId  = \DB::table('roles')->where('nama_role', 'orang_tua')->value('id_role');
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');

        $user = User::factory()->create(['role_id' => $roleId]);

        $ortuId = \DB::table('orang_tua')->insertGetId([
            'user_id'       => $user->id,
            'nama'          => 'Ortu Test ' . rand(100, 999),
            'jenis_kelamin' => 'Perempuan',
            'status'        => 'aktif',
        ]);

        // Buat kelas & siswa yang terhubung ke ortu ini
        $guruRoleId = \DB::table('roles')->where('nama_role', 'guru')->value('id_role');
        $guruUser   = User::factory()->create(['role_id' => $guruRoleId]);
        $guruId     = \DB::table('guru')->insertGetId([
            'user_id' => $guruUser->id,
            'nama'    => 'Guru Ortu Test',
            'status'  => 'aktif',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Kelas Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        $siswa = Siswa::create([
            'nama'          => 'Anak Test ' . rand(100, 999),
            'nis'           => 'NIS' . rand(10000, 99999),
            'nisn'          => (string) rand(1000000000, 9999999999),
            'jenis_kelamin' => 'L',
            'kelas_id'      => $kelas->id_kelas,
            'orang_tua_id'  => $ortuId,
            'status'        => 'aktif',
        ]);

        return compact('user', 'ortuId', 'kelas', 'siswa', 'tapelId');
    }

    // ============================================================
    // DASHBOARD ORANG TUA
    // ============================================================
    public function test_orangtua_dapat_akses_dashboard()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)->get('/orangtua/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_dashboard_orangtua()
    {
        $response = $this->get('/orangtua/dashboard');
        $response->assertRedirect('/');
    }

    // ============================================================
    // ABSENSI ORANG TUA
    // ============================================================
    public function test_orangtua_dapat_melihat_halaman_absensi_anak()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)->get('/orangtua/absensi');
        $response->assertStatus(200);
    }

    public function test_halaman_absensi_menampilkan_data_bulan_ini()
    {
        ['user' => $user, 'siswa' => $siswa] = $this->buatOrangTua();

        Absensi::create([
            'siswa_id'         => $siswa->id_siswa,
            'tanggal'          => now()->format('Y-m-d'),
            'hari'             => now()->translatedFormat('l'),
            'status_kehadiran' => 'H',
            'keterangan'       => null,
        ]);

        $response = $this->actingAs($user)->get('/orangtua/absensi');
        $response->assertStatus(200);
        $response->assertSee('Hadir');
    }

    public function test_orangtua_dapat_filter_absensi_per_bulan()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)
            ->get('/orangtua/absensi?bulan=1&tahun=2026');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_absensi_orangtua()
    {
        $response = $this->get('/orangtua/absensi');
        $response->assertRedirect('/');
    }

    // ============================================================
    // JADWAL ORANG TUA
    // ============================================================
    public function test_orangtua_dapat_melihat_halaman_jadwal_anak()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)->get('/orangtua/jadwal');
        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_jadwal_orangtua()
    {
        $response = $this->get('/orangtua/jadwal');
        $response->assertRedirect('/');
    }

    // ============================================================
    // DOKUMENTASI ORANG TUA
    // ============================================================
    public function test_orangtua_dapat_melihat_halaman_dokumentasi()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)->get('/orangtua/dokumentasi');
        $response->assertStatus(200);
    }

    public function test_orangtua_dapat_melihat_detail_dokumentasi()
    {
        ['user' => $user, 'kelas' => $kelas] = $this->buatOrangTua();

        $guruUser = User::factory()->create([
            'role_id' => \DB::table('roles')->where('nama_role', 'guru')->value('id_role')
        ]);

        $kegiatan = Kegiatan::create([
            'user_id'   => $guruUser->id,
            'kelas_id'  => $kelas->id_kelas,
            'judul'     => 'Kegiatan Kelas Test',
            'deskripsi' => 'Deskripsi kegiatan.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user)
            ->get("/orangtua/dokumentasi/{$kegiatan->id_kegiatan}");

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_dokumentasi_orangtua()
    {
        $response = $this->get('/orangtua/dokumentasi');
        $response->assertRedirect('/');
    }

    // ============================================================
    // PENGUMUMAN ORANG TUA
    // ============================================================
    public function test_orangtua_dapat_melihat_halaman_pengumuman()
    {
        ['user' => $user] = $this->buatOrangTua();

        $response = $this->actingAs($user)->get('/orangtua/pengumuman');
        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_pengumuman_orangtua()
    {
        $response = $this->get('/orangtua/pengumuman');
        $response->assertRedirect('/');
    }
}