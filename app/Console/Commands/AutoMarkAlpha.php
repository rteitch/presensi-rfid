<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoMarkAlpha extends Command
{
    protected $signature = 'attendance:auto-alpha';

    protected $description = 'Otomatis menandai siswa aktif yang tidak hadir/tap sebagai Alpha di akhir hari sekolah';

    public function handle(): int
    {
        $today = Carbon::today();

        // 1. Skip jika hari libur (Sabtu & Minggu)
        if ($today->isWeekend()) {
            $this->info("Hari ini ({$today->format('Y-m-d')}) adalah akhir pekan. Auto-Alpha dilewati.");
            return Command::SUCCESS;
        }

        // 2. Skip jika terdaftar di Kalender Libur Sekolah
        if (Holiday::isHoliday($today->toDateString())) {
            $this->info("Hari ini ({$today->format('Y-m-d')}) terdaftar sebagai Hari Libur Sekolah. Auto-Alpha dilewati.");
            return Command::SUCCESS;
        }

        // 3. Skip jika belum melewati jam pulang sekolah
        $setting = \App\Models\AttendanceSetting::whereHas('academicYear', fn ($q) => $q->where('is_active', true))->first() ?? \App\Models\AttendanceSetting::first();
        $jamPulang = $setting?->jam_pulang ?? '15:00:00';
        if (now()->format('H:i:s') < $jamPulang) {
            $this->info("Waktu saat ini (" . now()->format('H:i:s') . ") belum melewati jam pulang sekolah ({$jamPulang}). Auto-Alpha dilewati.");
            return Command::SUCCESS;
        }

        // 3. Ambil seluruh siswa aktif
        $activeStudents = Student::where('status', 'aktif')->get();
        $markedCount = 0;

        foreach ($activeStudents as $student) {
            $exists = Attendance::where('student_id', $student->id)
                ->where('tanggal', $today->toDateString())
                ->exists();

            if (!$exists) {
                Attendance::create([
                    'student_id' => $student->id,
                    'tanggal' => $today->toDateString(),
                    'status' => 'alpha',
                    'keterangan' => 'Otomatis sistem (Auto-Alpha)',
                ]);
                $markedCount++;
            }
        }

        $this->info("Proses Auto-Alpha selesai. {$markedCount} siswa ditandai Alpha untuk tanggal {$today->format('Y-m-d')}.");

        return Command::SUCCESS;
    }
}
