<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\AttendanceSetting;
use App\Models\Device;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RfidApiStateTest extends TestCase
{
    use RefreshDatabase;

    private string $deviceToken;

    protected function setUp(): void
    {
        parent::setUp();

        $year = AcademicYear::create([
            'nama' => '2025/2026 Testing',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        AttendanceSetting::create([
            'academic_year_id' => $year->id,
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '15:00:00',
            'toleransi_menit' => 15,
        ]);

        $kelas = SchoolClass::create([
            'nama_kelas' => 'VII-A Testing',
            'academic_year_id' => $year->id,
        ]);

        Student::create([
            'nis' => 'TEST001',
            'nama' => 'Siswa Aktif',
            'rfid_uid' => 'VALID_UID',
            'class_id' => $kelas->id,
            'status' => 'aktif',
        ]);

        Student::create([
            'nis' => 'TEST002',
            'nama' => 'Siswa Nonaktif',
            'rfid_uid' => 'NONAKTIF_UID',
            'class_id' => $kelas->id,
            'status' => 'nonaktif',
        ]);

        $this->deviceToken = 'test-device-token-'.Str::random(20);
        Device::create([
            'nama_device' => 'Test Device',
            'lokasi' => 'Test Location',
            'token_device' => $this->deviceToken,
            'is_active' => true,
        ]);
    }

    public function test_state_transition_attendance()
    {
        Carbon::setTestNow(Carbon::parse('06:50:00'));

        $response1 = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'VALID_UID']);
        $response1->assertStatus(200)
            ->assertJson(['success' => true, 'type' => 'masuk']);

        Carbon::setTestNow(Carbon::parse('06:52:00'));
        $response2 = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'VALID_UID']);
        $response2->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Mohon tunggu sejenak sebelum melakukan tap kembali.']);

        Carbon::setTestNow(Carbon::parse('15:10:00'));
        $response3 = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'VALID_UID']);
        $response3->assertStatus(200)
            ->assertJson(['success' => true, 'type' => 'pulang']);

        Carbon::setTestNow(Carbon::parse('15:15:00'));
        $response4 = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'VALID_UID']);
        $response4->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Anda sudah menyelesaikan presensi lengkap hari ini.']);
    }

    public function test_error_guessing_invalid_uid()
    {
        $response = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'UNKNOWN_UID']);
        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Kartu RFID Anda belum terdaftar di sistem']);

        $responseEmpty = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => '']);
        $responseEmpty->assertStatus(422);
    }

    public function test_error_guessing_inactive_student()
    {
        $response = $this->withHeader('X-Device-Token', $this->deviceToken)
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'NONAKTIF_UID']);
        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Kartu RFID Anda belum terdaftar di sistem']);
    }

    public function test_request_without_device_token_is_rejected()
    {
        $response = $this->postJson('/api/rfid/scan', ['uid' => 'VALID_UID']);
        $response->assertStatus(401);
    }

    public function test_request_with_invalid_device_token_is_rejected()
    {
        $response = $this->withHeader('X-Device-Token', 'invalid-token-12345')
            ->postJson('/api/rfid/scan', ['uid' => 'VALID_UID']);
        $response->assertStatus(401);
    }
}
