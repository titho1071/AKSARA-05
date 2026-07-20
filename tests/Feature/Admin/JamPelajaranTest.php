<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class JamPelajaranTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_crud_jam_pelajaran_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        $payload = [
            'jam_mulai' => '07:00',
            'jam_selesai' => '07:45',
            'keterangan' => 'Jam 1',
        ];

        $res = $this->postJson('/api/jam-pelajaran', $payload);
        $res->assertStatus(201)->assertJsonPath('success', true);
        $id = $res->json('data.id_jam');
        $this->assertNotEmpty($id);

        $this->putJson('/api/jam-pelajaran/' . $id, ['jam_mulai' => '08:00', 'jam_selesai' => '08:45'])
            ->assertStatus(200)->assertJsonPath('success', true);

        $this->deleteJson('/api/jam-pelajaran/' . $id)
            ->assertStatus(200)->assertJsonPath('success', true);
    }
}
