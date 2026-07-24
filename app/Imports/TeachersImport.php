<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements ToCollection, WithHeadingRow, WithValidation
{
    public array $imported = [];

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nip = trim($row['nip'] ?? '');
            $nama = trim($row['nama'] ?? '');
            $email = trim($row['email'] ?? '');

            if (! $nip || ! $nama) {
                $this->skipped++;

                continue;
            }

            $userId = null;
            if ($email) {
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nama,
                        'password' => bcrypt('password'),
                    ]
                );
                $user->assignRole('guru');
                $userId = $user->id;
            }

            $teacher = Teacher::updateOrCreate(
                ['nip' => $nip],
                [
                    'nama' => $nama,
                    'email' => $email ?: null,
                    'user_id' => $userId,
                    'no_hp' => $row['no_hp'] ?? null,
                    'mata_pelajaran' => $row['mata_pelajaran'] ?? null,
                    'status' => $row['status'] ?? 'aktif',
                ]
            );

            $this->imported[] = $teacher->nama;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|string',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'status' => 'nullable|in:aktif,nonaktif',
        ];
    }
}
