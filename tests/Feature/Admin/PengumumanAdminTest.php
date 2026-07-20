<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Pengumuman;

class PengumumanAdminTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_admin_dapat_melihat_daftar_pengumuman()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/pengumuman');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_halaman_create_pengumuman()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/pengumuman/create');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_halaman_edit_pengumuman()
    {
        $admin = $this->createAdmin();

        $pengumuman = Pengumuman::create([
            'judul' => 'Tes Pengumuman',
            'deskripsi' => 'Deskripsi',
            'kelas_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin/pengumuman/' . $pengumuman->id_pengumuman . '/edit');

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_detail_pengumuman()
    {
        $admin = $this->createAdmin();

        $pengumuman = Pengumuman::create([
            'judul' => 'Tes Pengumuman',
            'deskripsi' => 'Deskripsi',
            'kelas_id' => null,
        ]);

        $response = $this->actingAs($admin)->get('/admin/pengumuman/' . $pengumuman->id_pengumuman);

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_admin_pengumuman()
    {
        $response = $this->get('/admin/pengumuman');
        $response->assertRedirect('/');
    }

    public function test_admin_dapat_menghapus_pengumuman_via_api()
    {
        $admin = $this->createAdmin();

        $pengumuman = Pengumuman::create([
            'judul' => 'Tes Pengumuman Delete',
            'deskripsi' => 'Deskripsi',
            'kelas_id' => null,
        ]);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->deleteJson('/api/pengumuman/' . $pengumuman->id_pengumuman);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseMissing('pengumuman', ['id_pengumuman' => $pengumuman->id_pengumuman]);
    }

    public function test_admin_dapat_membuat_pengumuman_via_api()
    {
        $admin = $this->createAdmin();

        Sanctum::actingAs($admin, ['*']);

        $payload = [
            'judul' => 'Pengumuman API Create',
            'deskripsi' => 'Deskripsi pengumuman via API',
            'kelas_id' => null,
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(2)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/pengumuman', $payload);

        $response->assertStatus(201)->assertJson(['success' => true]);

        $createdId = $response->json('data.id_pengumuman');
        $this->assertNotEmpty($createdId);

        $this->assertDatabaseHas('pengumuman', ['id_pengumuman' => $createdId, 'judul' => $payload['judul']]);
    }

    public function test_admin_dapat_memperbarui_pengumuman_via_api()
    {
        $admin = $this->createAdmin();

        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman To Update',
            'deskripsi' => 'Deskripsi awal',
            'kelas_id' => null,
        ]);

        Sanctum::actingAs($admin, ['*']);

        $updatePayload = [
            'judul' => 'Pengumuman Updated',
            'deskripsi' => 'Deskripsi diperbarui',
        ];

        $response = $this->putJson('/api/pengumuman/' . $pengumuman->id_pengumuman, $updatePayload);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('pengumuman', ['id_pengumuman' => $pengumuman->id_pengumuman, 'judul' => $updatePayload['judul']]);
    }
}
