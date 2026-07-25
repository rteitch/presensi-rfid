<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private $attendances) {}

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'];
    }

    public function map($att): array
    {
        return [
            $att->tanggal,
            $att->jam_masuk ?? '-',
            $att->jam_pulang ?? '-',
            strtoupper($att->status),
            $att->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
