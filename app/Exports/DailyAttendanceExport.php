<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyAttendanceExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly string $bulan,
        private readonly ?int $classId = null,
    ) {}

    public function query()
    {
        return Attendance::with('student.schoolClass')
            ->where('tanggal', 'like', "{$this->bulan}%")
            ->when($this->classId, function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('class_id', $this->classId);
                });
            })
            ->orderBy('tanggal')
            ->orderBy('student_id');
    }

    public function headings(): array
    {
        return ['Tanggal', 'NIS', 'Nama Siswa', 'Kelas', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'];
    }

    public function map($attendance): array
    {
        return [
            $attendance->tanggal,
            $attendance->student->nis ?? '-',
            $attendance->student->nama ?? '-',
            $attendance->student->schoolClass->nama_kelas ?? '-',
            $attendance->jam_masuk ?? '-',
            $attendance->jam_pulang ?? '-',
            ucfirst($attendance->status),
            $attendance->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
