<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected array $headings;
    protected array $rows;

    public function __construct(array $headings, array $rows)
    {
        $this->headings = $headings;
        $this->rows = $rows;
    }

    public function array(): array
    {
        // Return data rows (one example row)
        return [$this->rows];
    }

    public function headings(): array
    {
        // Convert snake_case to Title Case for nicer column labels
        return array_map(fn($h) => ucwords(str_replace('_', ' ', $h)), $this->headings);
    }

    public function styles(Worksheet $sheet)
    {
        // Bold the heading row
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        // Force columns E and F (Nip, Nuptk) to be treated as text to avoid
        // scientific notation (E+) in Excel.
        return [
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
