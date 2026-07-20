<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class BiodataSiswaTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
        return User::factory()->create(['role_id' => $roleId]);
    }

    public function test_crud_biodata_siswa_via_api()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);

        // Ensure a Tahun Pelajaran exists for the kelas
        $tapelId = 'TP' . time();
        \DB::table('tahun_pelajaran')->insert([
            'id_tapel' => $tapelId,
            'semester' => '1',
            'tahun_pelajaran' => '2025/2026',
            'is_active' => 1,
        ]);

        // Create a kelas and attach to the tapel
        $kelasId = \DB::table('kelas')->insertGetId([
            'nama_kelas' => 'Kelas Test',
            'tingkat' => 1,
            'tapel_id' => $tapelId,
            'guru_id' => null,
        ]);

        $payload = [
            'nama' => 'Siswa API Test',
            'nis' => 'NIS' . rand(10000,99999),
            'nisn' => (string) rand(1000000000, 1999999999),
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasId,
        ];

        $res = $this->postJson('/api/siswa/biodata', $payload);
        $res->assertStatus(201)->assertJsonPath('status', 'success');
        $siswaId = $res->json('data.id_siswa') ?? $res->json('data.id');
        $this->assertNotEmpty($siswaId);

        $this->putJson('/api/siswa/biodata/' . $siswaId, [
            'nama' => 'Siswa Updated',
            'nis' => $payload['nis'],
            'nisn' => $payload['nisn'],
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasId,
        ])->assertStatus(200)->assertJsonPath('status', 'success');

        $this->deleteJson('/api/siswa/biodata/' . $siswaId)
            ->assertStatus(200)->assertJsonPath('status', 'success');
    }
}
