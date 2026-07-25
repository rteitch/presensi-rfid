<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\RfidLog;
use App\Models\Student;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;

class AttendanceService
{
    public function processScan(string $rfidUid, ?int $deviceId = null): array
    {
        $student = Student::where('rfid_uid', $rfidUid)->where('status', 'aktif')->first();

        if (! $student) {
            $this->logScan($rfidUid, $deviceId, false, 'Kartu RFID Anda belum terdaftar di sistem');

            return [
                'success' => false,
                'message' => 'Kartu RFID Anda belum terdaftar di sistem',
            ];
        }

        $today = Carbon::today();

        if (\App\Models\Holiday::isHoliday($today->toDateString())) {
            $this->logScan($rfidUid, $deviceId, false, 'Hari ini adalah Hari Libur Sekolah.', $student->id);

            return [
                'success' => false,
                'message' => 'Hari ini adalah Hari Libur Sekolah.',
            ];
        }

        $rawHariEfektif = \App\Models\SchoolSetting::get('hari_efektif');
        if ($rawHariEfektif !== null) {
            $hariEfektif = json_decode($rawHariEfektif, true) ?: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            if (! in_array($today->format('l'), $hariEfektif)) {
                $this->logScan($rfidUid, $deviceId, false, 'Hari ini adalah hari libur rutin sekolah.', $student->id);

                return [
                    'success' => false,
                    'message' => 'Hari ini adalah hari libur rutin sekolah.',
                ];
            }
        }
        
        $lockKey = "scan_lock_{$student->id}_{$today->toDateString()}";
        $lock = Cache::lock($lockKey, 5);

        if (! $lock->get()) {
            return [
                'success' => false,
                'message' => 'Mohon tunggu sejenak sebelum melakukan tap kembali.',
            ];
        }

        try {
            $attendance = Attendance::firstOrNew([
                'student_id' => $student->id,
                'tanggal' => $today->toDateString(),
            ]);

            $setting = AttendanceSetting::activeSetting();
            $result = $this->handleAttendance($attendance, $student, $setting);

            $this->logScan($rfidUid, $deviceId, $result['success'], $result['message'], $student->id);

            return $result;
        } finally {
            $lock->release();
        }
    }

    protected function handleAttendance(Attendance $attendance, Student $student, ?AttendanceSetting $setting): array
    {
        if (! $attendance->exists) {
            $attendance->jam_masuk = now();
            $attendance->status = $this->determineStatusMasuk(now(), $setting);
            $attendance->save();

            return [
                'success' => true,
                'type' => 'masuk',
                'student' => $student,
                'status' => $attendance->status,
                'message' => "Selamat datang dan semangat belajar, {$student->nama}!",
            ];
        }

        if ($attendance->jam_masuk && ! $attendance->jam_pulang) {
            $jamMasuk = Carbon::parse($attendance->jam_masuk);
            if (abs(now()->diffInMinutes($jamMasuk, false)) < 5) {
                return [
                    'success' => false,
                    'message' => 'Mohon tunggu sejenak sebelum melakukan tap kembali.',
                ];
            }
            $attendance->jam_pulang = now();
            $attendance->save();

            return [
                'success' => true,
                'type' => 'pulang',
                'student' => $student,
                'status' => $attendance->status,
                'message' => "Hati-hati di jalan, {$student->nama}!",
            ];
        }

        return [
            'success' => false,
            'message' => 'Anda sudah menyelesaikan presensi lengkap hari ini.',
        ];
    }

    protected function determineStatusMasuk(Carbon $waktuScan, ?AttendanceSetting $setting): string
    {
        $jamMasukSetting = $setting ? $setting->jam_masuk : '07:00:00';
        $toleransi = $setting ? $setting->toleransi_menit : 15;

        $batasTelat = Carbon::parse($jamMasukSetting)->addMinutes($toleransi);
        $jamScan = Carbon::parse($waktuScan->format('H:i:s'));

        return $jamScan->greaterThan($batasTelat) ? 'terlambat' : 'hadir';
    }

    protected function logScan(string $uid, ?int $deviceId, bool $valid, string $keterangan, ?int $studentId = null): void
    {
        RfidLog::create([
            'rfid_uid' => $uid,
            'student_id' => $studentId,
            'device_id' => $deviceId,
            'is_valid' => $valid,
            'keterangan' => $keterangan,
            'scanned_at' => now(),
        ]);
    }
}
