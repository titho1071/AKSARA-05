<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class TahunPelajaranTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_crud_tahun_pelajaran_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        $payload = [
            'semester' => '1',
            'tahun_pelajaran' => '2026/2027',
            'is_active' => 0,
        ];

        $res = $this->postJson('/api/tahun-pelajaran', $payload);
        $res->assertStatus(201);
        $tapelId = $res->json('id_tapel');
        $this->assertNotEmpty($tapelId);

        $this->putJson('/api/tahun-pelajaran/' . $tapelId, ['semester' => '2', 'tahun_pelajaran' => '2026/2027'])
            ->assertStatus(200);

        // delete (ensure it's not active)
        $this->deleteJson('/api/tahun-pelajaran/' . $tapelId)
            ->assertStatus(200);
    }
}
