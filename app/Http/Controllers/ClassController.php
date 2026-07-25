<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClassController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        $search = request('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $classes = SchoolClass::with(['waliKelas', 'academicYear', 'students'])
            ->when($isGuru, fn ($q) => $q->whereIn('id', $managedIds ?: [-1]))
            ->when($escaped, fn ($q) => $q->where('nama_kelas', 'like', "%{$escaped}%")
                ->orWhereHas('waliKelas', fn ($q2) => $q2->where('name', 'like', "%{$escaped}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $managedClassName = $isGuru ? $user->managedClasses()->pluck('nama_kelas')->join(', ') : null;

        return view('classes.index', compact('classes', 'search', 'isGuru', 'managedClassName'));
    }

    public function create()
    {
        $academicYears = AcademicYear::all();
        $gurus = User::role('guru')->get();

        return view('classes.create', compact('academicYears', 'gurus'));
    }

    public function store(StoreClassRequest $request)
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function show(Request $request, SchoolClass $class)
    {
        $user = $request->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        if ($isGuru && !in_array($class->id, $managedIds ?: [])) {
            abort(403, 'Anda hanya dapat mengakses data kelas binaan Anda.');
        }
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $year  = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);

        $students = $class->students()->orderBy('nama')
            ->withCount([
                'attendances as stat_hadir' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'hadir'),
                'attendances as stat_terlambat' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'terlambat'),
                'attendances as stat_izin' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'izin'),
                'attendances as stat_sakit' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'sakit'),
                'attendances as stat_alpha' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'alpha'),
            ])
            ->get();

        $totalSiswa    = $students->count();
        $avgHadir      = $totalSiswa > 0 ? round($students->avg(fn ($s) => $s->stat_hadir + $s->stat_terlambat)) : 0;
        $totalTerlambat = $students->sum('stat_terlambat');
        $totalAlpha    = $students->sum('stat_alpha');

        $class->load(['waliKelas', 'academicYear']);

        return view('classes.show', compact('class', 'students', 'bulan', 'avgHadir', 'totalTerlambat', 'totalAlpha', 'totalSiswa'));
    }

    public function exportExcel(Request $request, SchoolClass $class)
    {
        $user = $request->user();
        if ($user && $user->hasRole('guru') && !$user->hasRole('admin')) {
            if (!in_array($class->id, $user->managed_class_ids ?: [])) {
                abort(403, 'Anda hanya dapat mengunduh data kelas binaan Anda.');
            }
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $year  = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);

        $students = $class->students()->orderBy('nama')
            ->withCount([
                'attendances as stat_hadir' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'hadir'),
                'attendances as stat_terlambat' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'terlambat'),
                'attendances as stat_izin' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'izin'),
                'attendances as stat_sakit' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'sakit'),
                'attendances as stat_alpha' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'alpha'),
            ])
            ->get();

        $slugKelas = str_replace(' ', '-', strtolower($class->nama_kelas));
        $filename  = "rekap-kelas-{$slugKelas}-{$bulan}.xlsx";

        return Excel::download(new class($students) implements FromCollection, WithHeadings, WithMapping, WithStyles
        {
            public function __construct(private $students) {}

            public function collection() { return $this->students; }

            public function headings(): array
            {
                return ['No', 'NIS', 'Nama Siswa', 'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha', 'Total Kehadiran', 'No HP Ortu', 'Keterangan'];
            }

            public function map($s): array
            {
                static $no = 1;
                $total = $s->stat_hadir + $s->stat_terlambat;
                $catatan = [];
                if ($s->stat_terlambat >= 3) $catatan[] = 'Terlambat >=3x';
                if ($s->stat_alpha >= 2) $catatan[] = 'Alpha >=2x';

                return [
                    $no++,
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
                return [1 => ['font' => ['bold' => true]]];
            }
        }, $filename);
    }

    public function exportPdf(Request $request, SchoolClass $class)
    {
        $user = $request->user();
        if ($user && $user->hasRole('guru') && !$user->hasRole('admin')) {
            if (!in_array($class->id, $user->managed_class_ids ?: [])) {
                abort(403, 'Anda hanya dapat mengunduh data kelas binaan Anda.');
            }
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $year  = substr($bulan, 0, 4);
        $month = substr($bulan, 5, 2);

        $students = $class->students()
            ->withCount([
                'attendances as stat_hadir' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'hadir'),
                'attendances as stat_terlambat' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'terlambat'),
                'attendances as stat_izin' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'izin'),
                'attendances as stat_sakit' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'sakit'),
                'attendances as stat_alpha' => fn ($q) => $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->where('status', 'alpha'),
            ])
            ->orderBy('nama')
            ->get();

        $class->load(['waliKelas', 'academicYear']);

        $pdf = Pdf::loadView('classes.pdf', compact('class', 'students', 'bulan'));
        $slugKelas = str_replace(' ', '-', strtolower($class->nama_kelas));

        return $pdf->download("rekap-kelas-{$slugKelas}-{$bulan}.pdf");
    }

    public function edit(SchoolClass $class)
    {
        $academicYears = AcademicYear::all();
        $gurus = User::role('guru')->get();

        return view('classes.edit', compact('class', 'academicYears', 'gurus'));
    }

    public function update(UpdateClassRequest $request, SchoolClass $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas telah berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')->with('error', 'Gagal: Kelas masih memiliki siswa. Harap pindahkan atau hapus siswa terlebih dahulu untuk mencegah hilangnya data riwayat presensi.');
        }

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
