<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\JamPelajaran;
use App\Models\JadwalPelajaran;

class JadwalAdminTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_admin_dapat_melihat_halaman_jadwal()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/jadwal');

        $response->assertStatus(200);
    }

    public function test_guest_tidak_bisa_akses_admin_jadwal()
    {
        $response = $this->get('/admin/jadwal');
        $response->assertRedirect('/');
    }

    public function test_admin_dapat_membuat_memperbarui_dan_menghapus_jadwal_via_api()
    {
        $admin = $this->createAdmin();

        // buat jam pelajaran yang valid
        $jam = JamPelajaran::create([
            'jam_mulai' => '07:00',
            'jam_selesai' => '07:45',
            'keterangan' => 'Jam 1',
        ]);

        // authenticate using Sanctum for API routes
        Sanctum::actingAs($admin, ['*']);

        // create
        $createResponse = $this->postJson('/api/jadwal-pelajaran', [
            'hari' => 'Senin',
            'jam_id' => $jam->id_jam,
            'nama_kegiatan' => 'Kegiatan Test',
        ]);

        $createResponse->assertStatus(201)->assertJson(['success' => true]);

        $createdId = $createResponse->json('data.id');
        if (!$createdId) {
            // fallback: try common id fields
            $createdId = $createResponse->json('data.id_jadwal') ?: $createResponse->json('data.id');
        }

        $this->assertNotEmpty($createdId, 'Created jadwal id should be returned');

        // update
        $updateResponse = $this->putJson("/api/jadwal-pelajaran/{$createdId}", [
            'nama_kegiatan' => 'Kegiatan Updated',
        ]);

        $updateResponse->assertStatus(200)->assertJson(['success' => true]);

        // delete
        $deleteResponse = $this->deleteJson("/api/jadwal-pelajaran/{$createdId}");
        $deleteResponse->assertStatus(200)->assertJson(['success' => true]);
    }
}
