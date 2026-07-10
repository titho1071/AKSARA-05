<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['nama']) || empty($row['nis']) || empty($row['nisn'])) {
                continue;
            }

            if (Siswa::where('nis', $row['nis'])->orWhere('nisn', $row['nisn'])->exists()) {
                continue;
            }

            $kelasId = null;
            if (!empty($row['nama_kelas'])) {
                $kelasId = Kelas::where('nama_kelas', $row['nama_kelas'])->value('id_kelas');
            }

            $tanggal = $this->parseDate($row['tanggal_lahir'] ?? null);

            Siswa::create([
                'nama' => $row['nama'],
                'nis' => $row['nis'],
                'nisn' => $row['nisn'],
                'jenis_kelamin' => $this->normalizeGender($row['jenis_kelamin'] ?? null) ?? 'L',
                'kelas_id' => $kelasId,
                'alamat' => $row['alamat'] ?? null,
                'tanggal_lahir' => $tanggal,
                'status' => $this->normalizeStatus($row['status'] ?? null),
            ]);
        }
    }

    /**
     * Parse various date inputs from Excel into Y-m-d or null.
     * Supports Excel serial numbers and common string formats.
     */
    private function parseDate($value): ?string
    {
        if ($value === null || $value === '') return null;

        // If numeric, try PhpSpreadsheet excel serial -> DateTime
        if (is_numeric($value)) {
            try {
                $dt = PhpSpreadsheetDate::excelToDateTimeObject((float)$value);
                return $dt->format('Y-m-d');
            } catch (\Throwable $e) {
                // fallthrough
            }
        }

        // If already DateTime instance
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        // Try strtotime on common date strings
        $ts = strtotime((string)$value);
        if ($ts !== false && $ts > 0) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    private function normalizeGender($value): ?string
    {
        $v = strtolower(trim((string)($value ?? '')));
        if ($v === '') return null;

        $male = ['l', 'laki', 'laki-laki', 'laki laki', 'male'];
        $female = ['p', 'perempuan', 'wanita', 'female'];


        if (in_array($v, $male, true)) return 'L';
        if (in_array($v, $female, true)) return 'P';

        return null;
    }

    private function normalizeStatus($value): string
    {
        $v = strtolower(trim((string)($value ?? '')));
        if ($v === '') return 'aktif';

        $aktifValues = ['aktif', 'active', 'ya', 'yes'];
        $nonaktifValues = ['tidak aktif', 'tidak_aktif', 'nonaktif', 'non-active', 'no', 'tidak'];

        if (in_array($v, $aktifValues, true)) return 'aktif';
        if (in_array($v, $nonaktifValues, true)) return 'tidak_aktif';

        return 'aktif';
    }
}
