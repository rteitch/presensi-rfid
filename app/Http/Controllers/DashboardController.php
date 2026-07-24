<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\RfidLog;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $user = auth()->user();
        $isGuru = $user && $user->hasRole('guru') && !$user->hasRole('admin');
        $managedIds = $isGuru ? $user->managed_class_ids : null;

        // Base student query
        $studentQuery = Student::where('status', 'aktif');
        if ($isGuru) {
            $studentQuery->whereIn('class_id', $managedIds ?: [-1]);
        }

        $totalSiswa = (clone $studentQuery)->count();

        // Attendance base query
        $attQuery = Attendance::whereDate('tanggal', $today);
        if ($isGuru) {
            $attQuery->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
        }

        $hadirHariIni = (clone $attQuery)->where('status', 'hadir')->count();
        $terlambatHariIni = (clone $attQuery)->where('status', 'terlambat')->count();
        $izinSakitAlpha = (clone $attQuery)->whereIn('status', ['izin', 'sakit', 'alpha'])->count();
        $sudahPresensi = (clone $attQuery)->count();
        $belumPresensi = max(0, $totalSiswa - $sudahPresensi);

        // Recent RFID logs
        $logQuery = RfidLog::with('student')->latest('scanned_at');
        if ($isGuru) {
            $logQuery->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
        }
        $recentScanLogs = $logQuery->limit(10)->get();

        // Weekly chart data — last 7 days
        $weeklyData = [];
        $startDate = Carbon::today()->subDays(6)->toDateString();
        $endDate = Carbon::today()->toDateString();
        
        $weeklyQuery = Attendance::whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('tanggal, status, count(*) as count')
            ->groupBy('tanggal', 'status');

        if ($isGuru) {
            $weeklyQuery->whereHas('student', fn ($q) => $q->whereIn('class_id', $managedIds ?: [-1]));
        }

        $weeklyStats = $weeklyQuery->get()->groupBy('tanggal');

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            
            $dayStats = $weeklyStats->get($dateStr, collect());
            $hadir = $dayStats->where('status', 'hadir')->sum('count');
            $terlambat = $dayStats->where('status', 'terlambat')->sum('count');

            $weeklyData[] = [
                'label' => $date->isoFormat('ddd'),
                'date' => $dateStr,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'total' => $hadir + $terlambat,
                'is_today' => $date->isToday(),
            ];
        }

        $totals = array_column($weeklyData, 'total');
        $maxWeekly = max(array_merge([1], $totals));

        return view('dashboard', [
            'total_siswa' => $totalSiswa,
            'hadir_hari_ini' => $hadirHariIni,
            'terlambat_hari_ini' => $terlambatHariIni,
            'izin_sakit_alpha' => $izinSakitAlpha,
            'belum_presensi' => $belumPresensi,
            'recent_logs' => $recentScanLogs,
            'weekly_data' => $weeklyData,
            'max_weekly' => $maxWeekly,
        ]);
    }

    public function guide()
    {
        return view('guide');
    }
}
