<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Imports\TeachersImport;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facade\Excel;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $teachers = Teacher::query()
            ->when($escaped, function ($query, $escaped) {
                $query->where('nama', 'like', "%{$escaped}%")
                    ->orWhere('nip', 'like', "%{$escaped}%")
                    ->orWhere('mata_pelajaran', 'like', "%{$escaped}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'user.managedClasses']);

        return view('teachers.show', compact('teacher'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        $teacher = Teacher::create($data);

        if (! empty($teacher->email) && ! $teacher->user_id) {
            $user = User::firstOrCreate(
                ['email' => $teacher->email],
                [
                    'name' => $teacher->nama,
                    'password' => bcrypt('password'),
                ]
            );
            $user->assignRole('guru');
            $teacher->update(['user_id' => $user->id]);
        }

        return redirect()->route('teachers.index')->with('success', 'Data guru baru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($teacher->foto && Storage::disk('public')->exists($teacher->foto)) {
                Storage::disk('public')->delete($teacher->foto);
            }
            $data['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        $teacher->update($data);

        if (! empty($teacher->email) && ! $teacher->user_id) {
            $user = User::firstOrCreate(
                ['email' => $teacher->email],
                [
                    'name' => $teacher->nama,
                    'password' => bcrypt('password'),
                ]
            );
            $user->assignRole('guru');
            $teacher->update(['user_id' => $user->id]);
        }

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->foto && Storage::disk('public')->exists($teacher->foto)) {
            Storage::disk('public')->delete($teacher->foto);
        }
        
        $teacher->user?->delete();
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Data guru dan akun terkait berhasil dihapus dari sistem.');
    }

    public function import()
    {
        return view('teachers.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new TeachersImport;
        Excel::import($import, $request->file('file'));

        $count = count($import->imported);
        $skipped = $import->skipped;

        return redirect()->route('teachers.index')
            ->with('success', "Berhasil meng-import {$count} data guru! ({$skipped} data dilewati)");
    }

    public function export()
    {
        return Excel::download(new class implements FromCollection, WithHeadings, WithMapping
        {
            public function collection()
            {
                return Teacher::all();
            }

            public function headings(): array
            {
                return ['NIP', 'Nama', 'Email', 'No HP', 'Mata Pelajaran', 'Status'];
            }

            public function map($teacher): array
            {
                return [
                    $teacher->nip,
                    $teacher->nama,
                    $teacher->email,
                    $teacher->no_hp,
                    $teacher->mata_pelajaran,
                    $teacher->status,
                ];
            }
        }, 'data-guru.xlsx');
    }

    public function template()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return collect([
                    ['198501012010011001', 'Bu Sari', 'guru@sekolah.test', '081234567892', 'Matematika', 'aktif'],
                    ['198501012010011002', 'Pak Budi', 'pakbudi@sekolah.test', '081234567893', 'Bahasa Indonesia', 'aktif'],
                ]);
            }

            public function headings(): array
            {
                return ['nip', 'nama', 'email', 'no_hp', 'mata_pelajaran', 'status'];
            }
        }, 'template-import-guru.xlsx');
    }
}
