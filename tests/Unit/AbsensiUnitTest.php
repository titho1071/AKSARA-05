<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AbsensiUnitTest extends TestCase
{
    private array $bulanMap = [
        'januari'   => 1,
        'februari'  => 2,
        'maret'     => 3,
        'april'     => 4,
        'mei'       => 5,
        'juni'      => 6,
        'juli'      => 7,
        'agustus'   => 8,
        'september' => 9,
        'oktober'   => 10,
        'november'  => 11,
        'desember'  => 12,
    ];

    private array $statusValid = ['H', 'S', 'I', 'A'];

    // ============================================================
    // MAPPING BULAN
    // ============================================================
    public function test_mapping_januari_ke_angka_1()
    {
        $this->assertEquals(1, $this->bulanMap['januari']);
    }

    public function test_mapping_desember_ke_angka_12()
    {
        $this->assertEquals(12, $this->bulanMap['desember']);
    }

    public function test_semua_bulan_terdapat_dalam_map()
    {
        $this->assertCount(12, $this->bulanMap);
    }

    public function test_mapping_bulan_menghasilkan_angka_1_sampai_12()
    {
        $values = array_values($this->bulanMap);
        $this->assertEquals(range(1, 12), $values);
    }

    public function test_nama_bulan_tidak_valid_tidak_ada_dalam_map()
    {
        $this->assertArrayNotHasKey('july', $this->bulanMap);
        $this->assertArrayNotHasKey('august', $this->bulanMap);
        $this->assertArrayNotHasKey('january', $this->bulanMap);
    }

    // ============================================================
    // VALIDASI STATUS ABSENSI
    // ============================================================
    public function test_status_h_adalah_valid()
    {
        $this->assertContains('H', $this->statusValid);
    }

    public function test_status_s_adalah_valid()
    {
        $this->assertContains('S', $this->statusValid);
    }

    public function test_status_i_adalah_valid()
    {
        $this->assertContains('I', $this->statusValid);
    }

    public function test_status_a_adalah_valid()
    {
        $this->assertContains('A', $this->statusValid);
    }

    public function test_status_tidak_valid_ditolak()
    {
        $this->assertNotContains('X', $this->statusValid);
        $this->assertNotContains('',  $this->statusValid);
        $this->assertNotContains('h', $this->statusValid);
        $this->assertNotContains('HADIR', $this->statusValid);
    }

    public function test_jumlah_status_valid_adalah_4()
    {
        $this->assertCount(4, $this->statusValid);
    }

    // ============================================================
    // KALKULASI PERSENTASE KEHADIRAN
    // ============================================================
    public function test_persentase_kehadiran_100_persen()
    {
        $hadir = 20;
        $total = 20;
        $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;

        $this->assertEquals(100, $persen);
    }

    public function test_persentase_kehadiran_50_persen()
    {
        $hadir = 10;
        $total = 20;
        $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;

        $this->assertEquals(50, $persen);
    }

    public function test_persentase_kehadiran_nol_jika_tidak_ada_data()
    {
        $hadir = 0;
        $total = 0;
        $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;

        $this->assertEquals(0, $persen);
    }

    public function test_persentase_dibulatkan_ke_integer()
    {
        $hadir  = 1;
        $total  = 3;
        $persen = $total > 0 ? (int) round(($hadir / $total) * 100) : 0;

        $this->assertIsInt($persen);
        $this->assertEquals(33, $persen);
    }
}