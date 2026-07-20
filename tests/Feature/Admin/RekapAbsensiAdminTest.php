<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;

class RekapAbsensiAdminTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    private function ensureTapelExists(): string
    {
        $tapel = \DB::table('tahun_pelajaran')->where('is_active', 1)->first();
        if ($tapel) return $tapel->id_tapel;

        $id = (string) now()->year . '/' . (now()->year + 1);
        \DB::table('tahun_pelajaran')->insert([
            'id_tapel' => $id,
            'semester' => 'Ganjil',
            'tahun_pelajaran' => $id,
            'is_active' => 1,
        ]);
        return $id;
    }

    private function createKelasWithSiswa(): Kelas
    {
        $tapelId = $this->ensureTapelExists();

        $guruId = \DB::table('guru')->insertGetId([
            'user_id' => User::factory()->create()->id,
            'nama' => 'Guru Rekap',
            'status' => 'aktif',
        ]);

        $kelasId = \DB::table('kelas')->insertGetId([
            'nama_kelas' => 'Rekap ' . rand(100, 999),
            'tingkat' => 7,
            'guru_id' => $guruId,
            'tapel_id' => $tapelId,
        ]);

        $siswaId = \DB::table('siswa')->insertGetId([
            'nama' => 'Siswa Rekap',
            'nis' => 'R' . rand(10000,99999),
            'nisn' => (string) rand(1000000000, 1999999999),
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasId,
            'status' => 'aktif',
        ]);

        return Kelas::find($kelasId);
    }

    public function test_admin_dapat_melihat_halaman_rekap_admin()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/absensi/rekap');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_mendapatkan_daftar_kelas_rekap_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        $response = $this->getJson('/api/rekap-absensi/admin');

        $response->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_admin_dapat_melihat_preview_1bulan_via_api()
    {
        $admin = $this->createAdmin();
        $kelas = $this->createKelasWithSiswa();

        Sanctum::actingAs($admin, ['*']);

        $response = $this->getJson('/api/rekap-absensi/admin/1-bulan?kelas_id=' . $kelas->id_kelas . '&bulan=6');

        $response->assertStatus(200)->assertJsonPath('status', 'success');
    }
}
