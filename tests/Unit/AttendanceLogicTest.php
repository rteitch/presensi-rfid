<?php

namespace Tests\Unit;

use App\Models\AttendanceSetting;
use App\Services\AttendanceService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TestableAttendanceService extends AttendanceService
{
    public function publicDetermineStatusMasuk(Carbon $waktuScan, ?AttendanceSetting $setting): string
    {
        return $this->determineStatusMasuk($waktuScan, $setting);
    }
}

use PHPUnit\Framework\Attributes\DataProvider;

class AttendanceLogicTest extends TestCase
{
    #[DataProvider('boundaryTimeProvider')]
    public function test_determine_status_masuk_bva($scanTimeStr, $expectedStatus, $description)
    {
        $service = new TestableAttendanceService;

        // Mock Setting - 07:00 with 15 mins tolerance (limit is 07:15:00)
        $setting = new AttendanceSetting([
            'jam_masuk' => '07:00:00',
            'toleransi_menit' => 15,
        ]);

        $waktuScan = Carbon::parse($scanTimeStr);
        $status = $service->publicDetermineStatusMasuk($waktuScan, $setting);

        $this->assertEquals($expectedStatus, $status, $description);
    }

    public static function boundaryTimeProvider()
    {
        return [
            // ISTQB Boundary Value Analysis (BVA) for 07:15:00 limit
            ['07:14:59', 'hadir', 'Valid Bawah: 1 detik sebelum batas'],
            ['07:15:00', 'hadir', 'Valid Tepat: Tepat di batas toleransi'],
            ['07:15:01', 'terlambat', 'Invalid: 1 detik setelah batas (Terlambat)'],
            ['06:00:00', 'hadir', 'Valid Jauh Bawah: Datang sangat pagi'],
            ['08:00:00', 'terlambat', 'Invalid Jauh Atas: Sangat Terlambat'],
        ];
    }
}
