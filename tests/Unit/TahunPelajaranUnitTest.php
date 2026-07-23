<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TahunPelajaranUnitTest extends TestCase
{
    // ============================================================
    // HELPER — logika yang sama dipakai di controller
    // ============================================================
    private function getTahunDariBulan(string $tahunPelajaran, int $bulan): int
    {
        $tahunParts = explode('/', $tahunPelajaran);
        $tahunAwal  = (int) $tahunParts[0];
        $tahunAkhir = (int) explode('-', $tahunParts[1])[0];

        // Juli–Desember = tahun awal, Januari–Juni = tahun akhir
        return $bulan >= 7 ? $tahunAwal : $tahunAkhir;
    }

    private function getRangeBulanSemester(string $semester): array
    {
        if ($semester === 'Ganjil') {
            return ['awal' => 7, 'akhir' => 12];
        }
        return ['awal' => 1, 'akhir' => 6];
    }

    // ============================================================
    // PARSING TAHUN PELAJARAN
    // ============================================================
    public function test_parsing_tahun_awal_dari_format_tapel()
    {
        $tapel      = '2025/2026-Genap';
        $parts      = explode('/', $tapel);
        $tahunAwal  = (int) $parts[0];

        $this->assertEquals(2025, $tahunAwal);
    }

    public function test_parsing_tahun_akhir_dari_format_tapel()
    {
        $tapel      = '2025/2026-Genap';
        $parts      = explode('/', $tapel);
        $tahunAkhir = (int) explode('-', $parts[1])[0];

        $this->assertEquals(2026, $tahunAkhir);
    }

    public function test_parsing_semester_dari_format_tapel()
    {
        $tapel    = '2025/2026-Genap';
        $parts    = explode('/', $tapel);
        $semester = explode('-', $parts[1])[1];

        $this->assertEquals('Genap', $semester);
    }

    public function test_parsing_semester_ganjil_dari_format_tapel()
    {
        $tapel    = '2026/2027-Ganjil';
        $parts    = explode('/', $tapel);
        $semester = explode('-', $parts[1])[1];

        $this->assertEquals('Ganjil', $semester);
    }

    // ============================================================
    // LOGIKA TAHUN BERDASARKAN BULAN
    // ============================================================
    public function test_bulan_juli_menggunakan_tahun_awal()
    {
        $tahun = $this->getTahunDariBulan('2025/2026-Genap', 7);
        $this->assertEquals(2025, $tahun);
    }

    public function test_bulan_desember_menggunakan_tahun_awal()
    {
        $tahun = $this->getTahunDariBulan('2025/2026-Genap', 12);
        $this->assertEquals(2025, $tahun);
    }

    public function test_bulan_januari_menggunakan_tahun_akhir()
    {
        $tahun = $this->getTahunDariBulan('2025/2026-Genap', 1);
        $this->assertEquals(2026, $tahun);
    }

    public function test_bulan_juni_menggunakan_tahun_akhir()
    {
        $tahun = $this->getTahunDariBulan('2025/2026-Genap', 6);
        $this->assertEquals(2026, $tahun);
    }

    // ============================================================
    // RANGE BULAN PER SEMESTER
    // ============================================================
    public function test_semester_ganjil_dimulai_bulan_7()
    {
        $range = $this->getRangeBulanSemester('Ganjil');
        $this->assertEquals(7, $range['awal']);
    }

    public function test_semester_ganjil_berakhir_bulan_12()
    {
        $range = $this->getRangeBulanSemester('Ganjil');
        $this->assertEquals(12, $range['akhir']);
    }

    public function test_semester_genap_dimulai_bulan_1()
    {
        $range = $this->getRangeBulanSemester('Genap');
        $this->assertEquals(1, $range['awal']);
    }

    public function test_semester_genap_berakhir_bulan_6()
    {
        $range = $this->getRangeBulanSemester('Genap');
        $this->assertEquals(6, $range['akhir']);
    }

    public function test_semester_ganjil_mencakup_6_bulan()
    {
        $range  = $this->getRangeBulanSemester('Ganjil');
        $jumlah = $range['akhir'] - $range['awal'] + 1;

        $this->assertEquals(6, $jumlah);
    }

    public function test_semester_genap_mencakup_6_bulan()
    {
        $range  = $this->getRangeBulanSemester('Genap');
        $jumlah = $range['akhir'] - $range['awal'] + 1;

        $this->assertEquals(6, $jumlah);
    }
}