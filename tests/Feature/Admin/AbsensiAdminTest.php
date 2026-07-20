<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;

class AbsensiAdminTest extends TestCase
{
    use DatabaseTransactions;

    private function buatKelasDanSiswa(): array
    {
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');

        $guruId = \DB::table('guru')->insertGetId([
            'user_id' => User::factory()->create()->id,
            'nama'    => 'Guru Test',
            'status'  => 'aktif',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        $siswa = Siswa::create([
            'nama'          => 'Siswa Test',
            'nis'           => 'TEST' . rand(10000, 99999),
            'nisn'          => (string) rand(1000000000, 9999999999),
            'jenis_kelamin' => 'L',
            'kelas_id'      => $kelas->id_kelas,
            'status'        => 'aktif',
        ]);

        return compact('kelas', 'siswa');
    }

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_admin_dapat_melihat_halaman_index_absensi()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/absensi');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_halaman_pilih_bulan()
    {
        $admin = $this->createAdmin();
        ['kelas' => $kelas] = $this->buatKelasDanSiswa();

        $response = $this->actingAs($admin)->get("/admin/absensi/{$kelas->id_kelas}/pilih-bulan");

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_detail_absensi()
    {
        $admin = $this->createAdmin();
        ['kelas' => $kelas] = $this->buatKelasDanSiswa();

        $response = $this->actingAs($admin)->get("/admin/absensi/{$kelas->id_kelas}/detail/juni");

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_rekap_absensi()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/absensi/rekap');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_admin_absensi()
    {
        $response = $this->get('/admin/absensi');
        $response->assertRedirect('/');
    }
}
