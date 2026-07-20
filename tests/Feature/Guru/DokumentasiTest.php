<?php

namespace Tests\Feature\Guru;

use App\Models\User;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DokumentasiTest extends TestCase
{
    use DatabaseTransactions;

    private function buatGuru(): User
    {
        $roleId = \DB::table('roles')->where('nama_role', 'guru')->value('id_role');
        $user   = User::factory()->create(['role_id' => $roleId]);

        \DB::table('guru')->insert([
            'user_id' => $user->id,
            'nama'    => 'Guru Test ' . rand(100, 999),
            'status'  => 'aktif',
        ]);

        return $user;
    }

    public function test_guru_dapat_melihat_halaman_daftar_dokumentasi()
    {
        $user = $this->buatGuru();

        $response = $this->actingAs($user)->get('/guru/dokumentasi');
        $response->assertStatus(200);
    }

    public function test_guru_dapat_menambah_dokumentasi_dengan_foto()
    {
        Storage::fake('public');

        $user    = $this->buatGuru();
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');
        $guruId  = \DB::table('guru')->where('user_id', $user->id)->value('id_guru');

        $kelas = \App\Models\Kelas::create([
            'nama_kelas' => 'Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        $foto = UploadedFile::fake()->image('kegiatan.jpg', 800, 600);

        $response = $this->actingAs($user)->post('/guru/dokumentasi', [
            'judul'     => 'Kegiatan Lomba',
            'deskripsi' => 'Deskripsi kegiatan lomba antar kelas.',
            'tanggal'   => '2026-06-30',
            'kelas_id'  => $kelas->id_kelas,
            'foto'      => [$foto],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kegiatan', [
            'judul'   => 'Kegiatan Lomba',
            'user_id' => $user->id,
        ]);
    }

    public function test_tambah_dokumentasi_gagal_tanpa_foto()
    {
        $user = $this->buatGuru();

        $response = $this->actingAs($user)->post('/guru/dokumentasi', [
            'judul'     => 'Kegiatan Tanpa Foto',
            'deskripsi' => 'Deskripsi.',
            'tanggal'   => '2026-06-30',
            'kelas_id'  => 'semua_kelas',
        ]);

        $response->assertSessionHasErrors('foto');
        $this->assertDatabaseMissing('kegiatan', [
            'judul' => 'Kegiatan Tanpa Foto',
        ]);
    }

    public function test_guru_dapat_mengedit_dokumentasi_miliknya()
    {
        Storage::fake('public');

        $user     = $this->buatGuru();
        $kegiatan = Kegiatan::create([
            'user_id'   => $user->id,
            'kelas_id'  => 'semua_kelas',
            'judul'     => 'Judul Lama',
            'deskripsi' => 'Deskripsi lama.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user)
            ->put("/guru/dokumentasi/{$kegiatan->id_kegiatan}", [
                'judul'     => 'Judul Baru',
                'deskripsi' => 'Deskripsi baru.',
                'tanggal'   => '2026-06-30',
                'kelas_id'  => 'semua_kelas',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('kegiatan', [
            'id_kegiatan' => $kegiatan->id_kegiatan,
            'judul'       => 'Judul Baru',
        ]);
    }

    public function test_guru_tidak_bisa_edit_dokumentasi_milik_guru_lain()
    {
        $user1    = $this->buatGuru();
        $user2    = $this->buatGuru();
        $kegiatan = Kegiatan::create([
            'user_id'   => $user1->id,
            'kelas_id'  => 'semua_kelas',
            'judul'     => 'Kegiatan Guru 1',
            'deskripsi' => 'Deskripsi.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user2)
            ->put("/guru/dokumentasi/{$kegiatan->id_kegiatan}", [
                'judul'     => 'Coba Ubah',
                'deskripsi' => 'Coba.',
                'tanggal'   => '2026-06-30',
                'kelas_id'  => 'semua_kelas',
            ]);

        $response->assertStatus(403);
    }

    public function test_guru_dapat_menghapus_dokumentasi_miliknya()
    {
        Storage::fake('public');

        $user     = $this->buatGuru();
        $kegiatan = Kegiatan::create([
            'user_id'   => $user->id,
            'kelas_id'  => 'semua_kelas',
            'judul'     => 'Kegiatan Hapus',
            'deskripsi' => 'Deskripsi.',
            'tanggal'   => '2026-06-01',
            'status'    => 'aktif',
        ]);

        $response = $this->actingAs($user)
            ->delete("/guru/dokumentasi/{$kegiatan->id_kegiatan}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('kegiatan', [
            'id_kegiatan' => $kegiatan->id_kegiatan,
        ]);
    }

    public function test_guest_tidak_bisa_akses_dokumentasi()
    {
        $response = $this->get('/guru/dokumentasi');
        $response->assertRedirect('/');
    }
}