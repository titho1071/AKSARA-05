<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class BiodataAdminTest extends TestCase
{
	use DatabaseTransactions;

	private function createAdmin(): User
	{
		$roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');
		return User::factory()->create(['role_id' => $roleId]);
	}

	public function test_crud_biodata_admin_via_api()
	{
		$admin = $this->createAdmin();
		Sanctum::actingAs($admin, ['*']);

		$payload = [
			'nama' => 'Admin API Test',
			'email' => 'admin.api.' . rand(1000,9999) . '@example.test',
			'username' => 'admin_api_' . rand(1000,9999),
			'password' => 'password123',
			'password_confirmation' => 'password123',
		];

		$res = $this->postJson('/api/admin/biodata', $payload);
		$res->assertStatus(201)->assertJsonPath('status', 'success');
		$userId = $res->json('data.user_id');
		$this->assertNotEmpty($userId);

		$this->putJson('/api/admin/biodata/' . $userId, ['nama' => 'Admin Updated', 'email' => $payload['email'], 'username' => $payload['username']])
			->assertStatus(200)->assertJsonPath('status', 'success');

		$this->deleteJson('/api/admin/biodata/' . $userId)
			->assertStatus(200)->assertJsonPath('status', 'success');
	}
}

