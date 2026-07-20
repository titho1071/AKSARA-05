<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class KelasTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_crud_kelas_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        // make a tapel
        $tapelId = 'TP' . time();
        \DB::table('tahun_pelajaran')->insert([
            'id_tapel' => $tapelId,
            'semester' => '1',
            'tahun_pelajaran' => '2025/2026',
            'is_active' => 0,
        ]);

        $payload = [
            'nama_kelas' => 'Kelas API Test',
            'tingkat' => 1,
            'tapel_id' => $tapelId,
            'guru_id' => null,
        ];

        $res = $this->postJson('/api/kelas', $payload);
        $res->assertStatus(201);
        $kelasId = $res->json('id_kelas');
        $this->assertNotEmpty($kelasId);

        $this->putJson('/api/kelas/' . $kelasId, ['nama_kelas' => 'Kelas Updated', 'tingkat' => 1, 'tapel_id' => $tapelId])
            ->assertStatus(200);

        $this->deleteJson('/api/kelas/' . $kelasId)
            ->assertStatus(200);
    }
}
