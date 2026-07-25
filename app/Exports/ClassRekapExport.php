<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassRekapExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private int $no = 1;

    public function __construct(private $students) {}

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama Siswa', 'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha', 'Total Kehadiran', 'No HP Ortu', 'Keterangan'];
    }

    public function map($s): array
    {
        $total = $s->stat_hadir + $s->stat_terlambat;
        $catatan = [];
        if ($s->stat_terlambat >= 3) {
            $catatan[] = 'Terlambat >=3x';
        }
        if ($s->stat_alpha >= 2) {
            $catatan[] = 'Alpha >=2x';
        }

        return [
            $this->no++,
            $s->nis,
            $s->nama,
            $s->stat_hadir,
            $s->stat_terlambat,
            $s->stat_izin,
            $s->stat_sakit,
            $s->stat_alpha,
            $total,
            $s->no_hp_ortu ?? '-',
            implode(', ', $catatan) ?: 'Normal',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
