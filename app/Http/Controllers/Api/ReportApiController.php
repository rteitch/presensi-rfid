<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    /**
     * Serve data rekapitulasi presensi per-siswa untuk integrasi aplikasi lain.
     */
    public function rekap(Request $request): JsonResponse
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $classId = $request->input('class_id');

        $students = Student::with(['schoolClass'])
            ->where('status', 'aktif')
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->withCount([
                'attendances as total_hadir' => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'hadir'),
                'attendances as total_terlambat' => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'terlambat'),
                'attendances as total_izin' => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'izin'),
                'attendances as total_sakit' => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'sakit'),
                'attendances as total_alpha' => fn ($q) => $q->where('tanggal', 'like', "{$bulan}%")->where('status', 'alpha'),
            ])
            ->orderBy('nama')
            ->get();

        $data = $students->map(function ($s) {
            return [
                'student_id' => $s->id,
                'nis' => $s->nis,
                'nama' => $s->nama,
                'kelas' => $s->schoolClass->nama_kelas ?? '-',
                'rfid_uid' => $s->rfid_uid,
                'no_hp_ortu' => $s->no_hp_ortu,
                'rekap' => [
                    'hadir' => $s->total_hadir,
                    'terlambat' => $s->total_terlambat,
                    'izin' => $s->total_izin,
                    'sakit' => $s->total_sakit,
                    'alpha' => $s->total_alpha,
                    'total_kehadiran' => $s->total_hadir + $s->total_terlambat,
                ],
            ];
        });

        return response()->json([
            'status' => 'success',
            'periode_bulan' => $bulan,
            'total_siswa' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Serve data riwayat presensi harian siswa tertentu.
     */
    public function studentHistory(Request $request, Student $student): JsonResponse
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $attendances = $student->attendances()
            ->where('tanggal', 'like', "{$bulan}%")
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($att) {
                return [
                    'tanggal' => $att->tanggal,
                    'jam_masuk' => $att->jam_masuk,
                    'jam_pulang' => $att->jam_pulang,
                    'status' => $att->status,
                    'keterangan' => $att->keterangan,
                ];
            });

        return response()->json([
            'status' => 'success',
            'student' => [
                'id' => $student->id,
                'nis' => $student->nis,
                'nama' => $student->nama,
                'kelas' => $student->schoolClass->nama_kelas ?? '-',
            ],
            'periode_bulan' => $bulan,
            'total_record' => $attendances->count(),
            'data' => $attendances,
        ]);
    }
}
