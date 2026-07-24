<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public array $Imported = [];

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $classId = $this->resolveClassId($row['kelas'] ?? null);

            if (! $classId) {
                $this->skipped++;

                continue;
            }

            $student = Student::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'nama' => $row['nama'],
                    'rfid_uid' => $row['rfid_uid'] ?? null,
                    'class_id' => $classId,
                    'nama_ortu' => $row['nama_ortu'] ?? null,
                    'no_hp_ortu' => $row['no_hp_ortu'] ?? null,
                    'status' => $row['status'] ?? 'aktif',
                ]
            );

            $this->Imported[] = $student->nama;
        }
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|string',
            'nama' => 'required|string|max:255',
            'rfid_uid' => 'nullable|string',
            'kelas' => 'required|string',
            'nama_ortu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ];
    }

    private function resolveClassId(?string $namaKelas): ?int
    {
        if (! $namaKelas) {
            return null;
        }

        $class = SchoolClass::where('nama_kelas', 'like', trim($namaKelas))->first();

        return $class?->id;
    }
}
