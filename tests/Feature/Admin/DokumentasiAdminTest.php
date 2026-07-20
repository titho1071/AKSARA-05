<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Kelas;

class DokumentasiAdminTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    private function createKegiatanWithDokumentasi(): Kegiatan
    {
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');

        $guruUser = User::factory()->create(['role_id' => \DB::table('roles')->where('nama_role', 'guru')->value('id_role')]);

        $guruId = \DB::table('guru')->insertGetId([
            'user_id' => $guruUser->id,
            'nama'    => 'Guru Test',
            'status'  => 'aktif',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        $kegiatan = Kegiatan::create([
            'user_id' => $guruUser->id,
            'kelas_id' => $kelas->id_kelas,
            'judul' => 'Kegiatan Test ' . rand(1000, 9999),
            'deskripsi' => 'Deskripsi kegiatan test',
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        // Insert dokumentasi record
        \DB::table('dokumentasi')->insert([
            'id_kegiatan' => $kegiatan->id_kegiatan,
            'foto' => 'path/to/foto.jpg',
        ]);

        return $kegiatan->fresh();
    }

    public function test_admin_dapat_melihat_daftar_dokumentasi()
    {
        $admin = $this->createAdmin();
        $this->createKegiatanWithDokumentasi();

        $response = $this->actingAs($admin)->get('/admin/dokumentasi');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_detail_dokumentasi()
    {
        $admin = $this->createAdmin();
        $kegiatan = $this->createKegiatanWithDokumentasi();

        $response = $this->actingAs($admin)->get('/admin/dokumentasi/' . $kegiatan->id_kegiatan);

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_admin_dokumentasi()
    {
        $response = $this->get('/admin/dokumentasi');
        $response->assertRedirect('/');
    }
}
