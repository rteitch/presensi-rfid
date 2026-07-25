<?php

namespace App\Http\Controllers;

use App\Exports\DailyAttendanceExport;
use App\Exports\RekapAttendanceExport;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private function getScopedClassesAndClassId(Request $request): array
    {
        $user = $request->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        if ($isGuru) {
            $classes = SchoolClass::whereIn('id', $managedIds ?: [-1])->get();
            $classId = $request->input('class_id');
            if (!$classId || !in_array($classId, $managedIds ?: [])) {
                $classId = $managedIds[0] ?? null;
            }
        } else {
            $classes = SchoolClass::all();
            $classId = $request->input('class_id');
        }

        return [$classes, $classId, $isGuru, $managedIds];
    }

    /**
     * Rekap laporan bulanan presensi.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        $attendances = Attendance::with('student.schoolClass')
            ->where('tanggal', 'like', "{$bulan}%")
            ->when($isGuru, function ($query) use ($managedIds) {
                $query->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
            })
            ->when($classId, function ($query, $classId) {
                $query->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            })
            ->latest('tanggal')
            ->paginate(30)
            ->withQueryString();

        return view('reports.index', compact('attendances', 'bulan', 'classes', 'classId'));
    }

    /**
     * Rekap presensi per-siswa sebulan, dengan tombol WA ke orang tua.
     */
    public function rekap(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        // Hitung rekap per siswa: hadir, terlambat, izin, pulang_cepat, dispensasi, sakit, alpha
        $students = Student::with(['schoolClass'])
            ->where('students.status', 'aktif')
            ->when($isGuru, fn ($q) => $q->whereIn('students.class_id', $managedIds ?: [-1]))
            ->when($classId, fn ($q) => $q->where('students.class_id', $classId))
            ->leftJoin('attendances', function($join) use ($bulan) {
                $join->on('students.id', '=', 'attendances.student_id')
                     ->where('attendances.tanggal', 'like', "{$bulan}%");
            })
            ->selectRaw("
                students.*,
                SUM(CASE WHEN attendances.status = 'hadir' THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN attendances.status = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat,
                SUM(CASE WHEN attendances.status = 'izin' THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN attendances.status = 'pulang_cepat' THEN 1 ELSE 0 END) as total_pulang_cepat,
                SUM(CASE WHEN attendances.status = 'dispensasi' THEN 1 ELSE 0 END) as total_dispensasi,
                SUM(CASE WHEN attendances.status = 'sakit' THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN attendances.status = 'alpha' THEN 1 ELSE 0 END) as total_alpha
            ")
            ->groupBy('students.id')
            ->orderByDesc('total_terlambat')
            ->get();

        return view('reports.rekap', compact('students', 'bulan', 'classes', 'classId'));
    }

    /**
     * Leaderboard siswa paling sering terlambat (all-time atau per bulan).
     */
    public function leaderboard(Request $request)
    {
        $bulan = $request->input('bulan', '');
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);
        $limit = 20;

        $students = Student::with(['schoolClass'])
            ->where('status', 'aktif')
            ->when($isGuru, fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]))
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->withCount([
                'attendances as total_terlambat' => function ($q) use ($bulan) {
                    $q->where('status', 'terlambat');
                    if ($bulan) {
                        $q->where('tanggal', 'like', "{$bulan}%");
                    }
                },
                'attendances as total_hadir' => function ($q) use ($bulan) {
                    $q->where('status', 'hadir');
                    if ($bulan) {
                        $q->where('tanggal', 'like', "{$bulan}%");
                    }
                },
                'attendances as total_alpha' => function ($q) use ($bulan) {
                    $q->where('status', 'alpha');
                    if ($bulan) {
                        $q->where('tanggal', 'like', "{$bulan}%");
                    }
                },
            ])
            ->orderByDesc('total_terlambat')
            ->limit($limit)
            ->get();

        return view('reports.leaderboard', compact('students', 'bulan', 'classes', 'classId'));
    }

    /**
     * Leaderboard Publik Siswa Terlambat (Tanpa Auth / Akses Umum)
     */
    public function publicLeaderboard(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $classId = $request->input('class_id');
        $limit = 10;
        $classes = SchoolClass::all();

        $students = Student::with(['schoolClass'])
            ->where('status', 'aktif')
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->withCount([
                'attendances as total_terlambat' => function ($q) use ($bulan) {
                    $q->where('status', 'terlambat');
                    if ($bulan) {
                        $q->where('tanggal', 'like', "{$bulan}%");
                    }
                },
                'attendances as total_hadir' => function ($q) use ($bulan) {
                    $q->where('status', 'hadir');
                    if ($bulan) {
                        $q->where('tanggal', 'like', "{$bulan}%");
                    }
                },
            ])
            ->orderByDesc('total_terlambat')
            ->limit($limit)
            ->get();

        return view('reports.public_leaderboard', compact('students', 'bulan', 'classes', 'classId'));
    }

    /**
     * Export rekap bulanan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        $attendances = Attendance::with('student.schoolClass')
            ->where('tanggal', 'like', "{$bulan}%")
            ->when($isGuru, function ($query) use ($managedIds) {
                $query->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
            })
            ->when($classId, function ($query, $classId) {
                $query->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            })
            ->get();

        $pdf = Pdf::loadView('reports.pdf', compact('attendances', 'bulan'));

        return $pdf->download("laporan-presensi-{$bulan}.pdf");
    }

    /**
     * Export rekap per-siswa ke PDF.
     */
    public function exportRekapPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        $students = Student::with(['schoolClass'])
            ->where('status', 'aktif')
            ->when($isGuru, fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]))
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->withCount([
                'attendances as total_hadir'       => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'hadir'),
                'attendances as total_terlambat'   => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'terlambat'),
                'attendances as total_izin'        => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'izin'),
                'attendances as total_pulang_cepat'=> fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'pulang_cepat'),
                'attendances as total_dispensasi'  => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'dispensasi'),
                'attendances as total_sakit'       => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'sakit'),
                'attendances as total_alpha'       => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'alpha'),
            ])
            ->orderByDesc('total_terlambat')
            ->get();

        $pdf = Pdf::loadView('reports.rekap_pdf', compact('students', 'bulan'));

        return $pdf->download("rekap-presensi-siswa-{$bulan}.pdf");
    }

    /**
     * Export laporan harian ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        return Excel::download(new DailyAttendanceExport($bulan, $classId), "laporan-presensi-{$bulan}.xlsx");
    }

    /**
     * Export rekap per-siswa ke Excel.
     */
    public function exportRekapExcel(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$classes, $classId, $isGuru, $managedIds] = $this->getScopedClassesAndClassId($request);

        return Excel::download(new RekapAttendanceExport($bulan, $classId), "rekap-presensi-siswa-{$bulan}.xlsx");
    }
}
