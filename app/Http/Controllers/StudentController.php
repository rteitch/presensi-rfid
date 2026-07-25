<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $students = Student::with('schoolClass')
            ->when($isGuru, fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]))
            ->when($escaped, function ($query, $escaped) {
                $query->where(function ($q) use ($escaped) {
                    $q->where('nama', 'like', "%{$escaped}%")
                        ->orWhere('nis', 'like', "%{$escaped}%")
                        ->orWhere('rfid_uid', 'like', "%{$escaped}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $managedClassName = $isGuru ? $user->managedClasses()->pluck('nama_kelas')->join(', ') : null;

        return view('students.index', compact('students', 'search', 'isGuru', 'managedClassName'));
    }

    public function create()
    {
        $classes = SchoolClass::all();

        return view('students.create', compact('classes'));
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data siswa baru berhasil ditambahkan.');
    }

    public function import()
    {
        return view('students.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new StudentsImport;
        Excel::import($import, $request->file('file'));

        $count = count($import->Imported);
        $skipped = $import->skipped;

        if ($count === 0 && $skipped === 0) {
            return redirect()->route('students.import')->with('error', 'File kosong atau format tidak sesuai.');
        }

        $message = "Berhasil import {$count} data siswa.";
        if ($skipped > 0) {
            $message .= " ({$skipped} baris dilewati karena kelas tidak ditemukan).";
        }

        return redirect()->route('students.index')->with('success', $message);
    }

    public function export()
    {
        return Excel::download(new class implements FromCollection, WithHeadings, WithMapping
        {
            public function collection()
            {
                return Student::with('schoolClass')->get();
            }

            public function headings(): array
            {
                return ['NIS', 'Nama', 'Jenis Kelamin', 'Agama', 'RFID UID', 'Kelas', 'Nama Orang Tua', 'No HP Ortu', 'Status'];
            }

            public function map($student): array
            {
                return [
                    $student->nis,
                    $student->nama,
                    $student->jenis_kelamin === 'L' ? 'Laki-laki' : ($student->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                    $student->agama ?: '-',
                    $student->rfid_uid,
                    $student->schoolClass->nama_kelas ?? '-',
                    $student->nama_ortu,
                    $student->no_hp_ortu,
                    $student->status,
                ];
            }
        }, 'data-siswa.xlsx');
    }

    public function template()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return collect([
                    ['2025001', 'Ahmad Fauzan',    'L', 'Islam', '04A1B2C3', 'VII-A', 'Bapak Fauzan',    '081234567890', 'aktif'],
                    ['2025002', 'Siti Nurhaliza',  'P', 'Islam', '04B2C3D4', 'VII-A', 'Bapak Nurhaliza', '081234567891', 'aktif'],
                ]);
            }

            public function headings(): array
            {
                return ['nis', 'nama', 'jenis_kelamin', 'agama', 'rfid_uid', 'kelas', 'nama_ortu', 'no_hp_ortu', 'status'];
            }
        }, 'template-import-siswa.xlsx');
    }

    public function exportAttendance(Request $request, Student $student)
    {
        $user = $request->user();
        if ($user && $user->hasRole('guru') && !$user->hasRole('admin')) {
            if (!in_array($student->class_id, $user->managed_class_ids ?: [])) {
                abort(403, 'Anda hanya dapat mengunduh data presensi siswa dari kelas binaan Anda.');
            }
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $year  = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);

        $attendances = $student->attendances()
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->get();

        $namaFile = 'presensi-' . str_replace(' ', '-', strtolower($student->nama)) . '-' . $bulan . '.xlsx';

        return Excel::download(new \App\Exports\StudentAttendanceExport($attendances), $namaFile);
    }

    public function show(Request $request, Student $student)
    {
        $user = $request->user();
        if ($user && $user->hasRole('guru') && !$user->hasRole('admin')) {
            if (!in_array($student->class_id, $user->managed_class_ids)) {
                abort(403, 'Anda hanya dapat mengakses data siswa dari kelas binaan Anda.');
            }
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $attendances = $student->attendances()
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal', 'desc')
            ->get();

        $stats = [
            'total_hadir' => $student->attendances()->whereYear('tanggal', substr($bulan, 0, 4))->whereMonth('tanggal', substr($bulan, 5, 2))->where('status', 'hadir')->count(),
            'total_terlambat' => $student->attendances()->whereYear('tanggal', substr($bulan, 0, 4))->whereMonth('tanggal', substr($bulan, 5, 2))->where('status', 'terlambat')->count(),
            'total_izin' => $student->attendances()->whereYear('tanggal', substr($bulan, 0, 4))->whereMonth('tanggal', substr($bulan, 5, 2))->where('status', 'izin')->count(),
            'total_sakit' => $student->attendances()->whereYear('tanggal', substr($bulan, 0, 4))->whereMonth('tanggal', substr($bulan, 5, 2))->where('status', 'sakit')->count(),
            'total_alpha' => $student->attendances()->whereYear('tanggal', substr($bulan, 0, 4))->whereMonth('tanggal', substr($bulan, 5, 2))->where('status', 'alpha')->count(),
        ];

        $totalRecords = array_sum($stats);
        $pctHadir = $totalRecords > 0 ? round((($stats['total_hadir'] + $stats['total_terlambat']) / $totalRecords) * 100) : 0;

        return view('students.show', compact('student', 'attendances', 'stats', 'bulan', 'pctHadir'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::all();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($student->foto && Storage::disk('public')->exists($student->foto)) {
                Storage::disk('public')->delete($student->foto);
            }
            $data['foto'] = $request->file('foto')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data profil siswa telah berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->foto && Storage::disk('public')->exists($student->foto)) {
            Storage::disk('public')->delete($student->foto);
        }
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus dari sistem.');
    }
}
