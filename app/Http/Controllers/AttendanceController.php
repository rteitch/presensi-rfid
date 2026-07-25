<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceManualRequest;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        $tanggal = $request->input('tanggal', now()->toDateString());
        $classId = $request->input('class_id');
        $search = $request->input('search');

        if ($isGuru) {
            $classes = SchoolClass::whereIn('id', $managedIds ?: [-1])->get();
            if (!$classId || !in_array($classId, $managedIds ?: [])) {
                $classId = $managedIds[0] ?? null;
            }
        } else {
            $classes = SchoolClass::all();
        }

        $attendances = Attendance::with('student.schoolClass')
            ->whereDate('tanggal', $tanggal)
            ->when($isGuru, function ($query) use ($managedIds) {
                $query->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
            })
            ->when($classId, function ($query, $classId) {
                $query->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $students = Student::where('status', 'aktif')
            ->when($isGuru, fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]))
            ->with('schoolClass')
            ->get();

        return view('attendances.index', compact('attendances', 'tanggal', 'classes', 'classId', 'students', 'search'));
    }

    public function storeManual(StoreAttendanceManualRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        if ($user->hasRole('guru') && ! $user->hasRole('admin')) {
            $student = Student::findOrFail($data['student_id']);
            $managedIds = $user->managed_class_ids ?: [];
            if (! in_array($student->class_id, $managedIds)) {
                abort(403, 'Anda tidak berhak memasukkan presensi untuk siswa di luar kelas binaan Anda.');
            }
        }

        Attendance::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'tanggal' => $data['tanggal'],
            ],
            [
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ]
        );

        return back()->with('success', 'Data presensi manual telah berhasil disimpan.');
    }
}
