<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class MataPelajaranTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_crud_mata_pelajaran_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        $payload = [
            'nama_mapel' => 'Mapel API Test ' . rand(100,999),
        ];

        $res = $this->postJson('/api/mata-pelajaran', $payload);
        $res->assertStatus(201)->assertJsonPath('success', true);
        $id = $res->json('data.id_mapel');
        $this->assertNotEmpty($id);

        $this->putJson('/api/mata-pelajaran/' . $id, ['nama_mapel' => 'Mapel Updated'])
            ->assertStatus(200)->assertJsonPath('success', true);

        $this->deleteJson('/api/mata-pelajaran/' . $id)
            ->assertStatus(200)->assertJsonPath('success', true);
    }
}
