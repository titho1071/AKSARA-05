<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_login_dapat_diakses()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_berhasil_dengan_kredensial_valid()
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');

        $user = User::factory()->create([
            'email'    => 'testadmin_' . rand(1000,9999) . '@test.com',
            'password' => bcrypt('password123'),
            'role_id'  => $roleId,
        ]);

        $response = $this->post('/login', [
            'login'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_login_gagal_dengan_password_salah()
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');

        $user = User::factory()->create([
            'email'    => 'testadmin2_' . rand(1000,9999) . '@test.com',
            'password' => bcrypt('password123'),
            'role_id'  => $roleId,
        ]);

        $response = $this->post('/login', [
            'login'    => $user->email,
            'password' => 'salah123',
        ]);

        $this->assertGuest();
    }

    public function test_logout_berhasil()
    {
        $roleId = \DB::table('roles')->where('nama_role', 'guru')->value('id_role');

        $user = User::factory()->create(['role_id' => $roleId]);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_admin_diarahkan_ke_dashboard_admin()
    {
        $roleId = \DB::table('roles')->where('nama_role', 'admin')->value('id_role');

        $user = User::factory()->create([
            'email'    => 'testadmin3_' . rand(1000,9999) . '@test.com',
            'password' => bcrypt('password123'),
            'role_id'  => $roleId,
        ]);

        $this->post('/login', [
            'login'    => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_guest_tidak_bisa_akses_dashboard()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/');
    }
}