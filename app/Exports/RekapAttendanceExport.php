<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAttendanceExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly string $bulan,
        private readonly ?int $classId = null,
    ) {}

    public function query()
    {
        return Student::with('schoolClass')
            ->where('status', 'aktif')
            ->when($this->classId, fn ($q) => $q->where('class_id', $this->classId))
            ->withCount([
                'attendances as total_hadir'        => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'hadir'),
                'attendances as total_terlambat'    => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'terlambat'),
                'attendances as total_izin'         => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'izin'),
                'attendances as total_pulang_cepat' => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'pulang_cepat'),
                'attendances as total_dispensasi'   => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'dispensasi'),
                'attendances as total_sakit'        => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'sakit'),
                'attendances as total_alpha'        => fn ($q) => $q->where('tanggal', 'like', "{$this->bulan}%")->where('status', 'alpha'),
            ])
            ->orderByDesc('total_terlambat');
    }

    public function headings(): array
    {
        return ['NIS', 'Nama Siswa', 'Kelas', 'Hadir', 'Terlambat', 'Izin', 'Pulang Cepat', 'Dispensasi', 'Sakit', 'Alpha', 'Total', 'Keterangan'];
    }

    public function map($student): array
    {
        $total = $student->total_hadir + $student->total_terlambat + $student->total_izin + ($student->total_pulang_cepat ?? 0) + ($student->total_dispensasi ?? 0) + $student->total_sakit + $student->total_alpha;
        $ket = '';
        if ($student->total_terlambat >= 3 || $student->total_alpha >= 2) {
            $ket = 'PERLU PERHATIAN';
        }

        return [
            $student->nis,
            $student->nama,
            $student->schoolClass->nama_kelas ?? '-',
            $student->total_hadir,
            $student->total_terlambat,
            $student->total_izin,
            $student->total_pulang_cepat ?? 0,
            $student->total_dispensasi ?? 0,
            $student->total_sakit,
            $student->total_alpha,
            $total,
            $ket,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
