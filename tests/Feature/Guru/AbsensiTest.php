<?php

namespace Tests\Feature\Guru;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AbsensiTest extends TestCase
{
    use DatabaseTransactions;

    private function buatGuruDanKelas(): array
    {
        $roleId  = \DB::table('roles')->where('nama_role', 'guru')->value('id_role');
        $tapelId = \DB::table('tahun_pelajaran')->where('is_active', 1)->value('id_tapel');

        $user = User::factory()->create(['role_id' => $roleId]);

        $guruId = \DB::table('guru')->insertGetId([
            'user_id' => $user->id,
            'nama'    => 'Guru Test',
            'status'  => 'aktif',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'Test ' . rand(100, 999),
            'tingkat'    => 7,
            'guru_id'    => $guruId,
            'tapel_id'   => $tapelId,
        ]);

        $siswa = Siswa::create([
            'nama'          => 'Siswa Test',
            'nis'           => 'TEST' . rand(10000, 99999),
            'nisn'          => (string) rand(1000000000, 9999999999),
            'jenis_kelamin' => 'L',
            'kelas_id'      => $kelas->id_kelas,
            'status'        => 'aktif',
        ]);

        return compact('user', 'kelas', 'siswa');
    }

    public function test_guru_dapat_melihat_halaman_kelola_absensi()
    {
        ['user' => $user, 'kelas' => $kelas] = $this->buatGuruDanKelas();

        $response = $this->actingAs($user)
            ->get("/guru/absensi/{$kelas->id_kelas}/kelola/juni/30");

        $response->assertStatus(200);
    }

    public function test_guru_dapat_menyimpan_absensi_hadir()
    {
        ['user' => $user, 'kelas' => $kelas, 'siswa' => $siswa] = $this->buatGuruDanKelas();

        $response = $this->actingAs($user)
            ->post("/guru/absensi/{$kelas->id_kelas}/kelola/juni/30", [
                '_token'      => csrf_token(),
                'status'      => [$siswa->id_siswa => 'H'],
                'keterangan'  => [$siswa->id_siswa => ''],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('absensi', [
            'siswa_id'         => $siswa->id_siswa,
            'status_kehadiran' => 'H',
        ]);
    }

    public function test_guru_dapat_menyimpan_absensi_sakit_dengan_keterangan()
    {
        ['user' => $user, 'kelas' => $kelas, 'siswa' => $siswa] = $this->buatGuruDanKelas();

        $response = $this->actingAs($user)
            ->post("/guru/absensi/{$kelas->id_kelas}/kelola/juni/30", [
                '_token'     => csrf_token(),
                'status'     => [$siswa->id_siswa => 'S'],
                'keterangan' => [$siswa->id_siswa => 'Demam tinggi'],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('absensi', [
            'siswa_id'         => $siswa->id_siswa,
            'status_kehadiran' => 'S',
            'keterangan'       => 'Demam tinggi',
        ]);
    }

    public function test_status_absensi_hanya_boleh_h_s_i_a()
    {
        ['user' => $user, 'kelas' => $kelas, 'siswa' => $siswa] = $this->buatGuruDanKelas();

        $this->actingAs($user)
            ->post("/guru/absensi/{$kelas->id_kelas}/kelola/juni/30", [
                '_token'     => csrf_token(),
                'status'     => [$siswa->id_siswa => 'X'],
                'keterangan' => [$siswa->id_siswa => ''],
            ]);

        $this->assertDatabaseMissing('absensi', [
            'siswa_id'         => $siswa->id_siswa,
            'status_kehadiran' => 'X',
        ]);
    }

    public function test_guest_tidak_bisa_akses_halaman_absensi()
    {
        $response = $this->get('/guru/absensi');
        $response->assertRedirect('/');
    }
}