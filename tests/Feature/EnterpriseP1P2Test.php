<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EnterpriseP1P2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_health_check_endpoint_returns_healthy()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'healthy',
                'services' => [
                    'database' => 'connected',
                    'cache' => 'operational',
                ],
            ]);
    }

    public function test_auto_mark_alpha_command()
    {
        Carbon::setTestNow('2026-07-27 18:00:00'); // Monday evening after jam_pulang

        $ay = AcademicYear::create([
            'nama' => '2025/2026 Ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'is_active' => true,
        ]);
        $class = SchoolClass::create(['nama_kelas' => 'X-IPA-1', 'academic_year_id' => $ay->id]);
        $student = Student::create([
            'class_id' => $class->id,
            'nis' => '11223344',
            'nama' => 'Budi Santoso',
            'rfid_uid' => '04A1B2C3',
            'status' => 'aktif',
        ]);

        $this->artisan('attendance:auto-alpha')
            ->assertExitCode(0);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'tanggal' => '2026-07-27',
            'status' => 'alpha',
        ]);

        Carbon::setTestNow();
    }

    public function test_auto_mark_alpha_skips_weekend()
    {
        Carbon::setTestNow('2026-07-26'); // Sunday

        $this->artisan('attendance:auto-alpha')
            ->expectsOutputToContain('akhir pekan')
            ->assertExitCode(0);

        Carbon::setTestNow();
    }

    public function test_holiday_controller_update()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $holiday = Holiday::create([
            'nama_libur' => 'Libur Awal',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('holidays.update', $holiday), [
                'nama_libur' => 'Libur Revisi',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-08-03',
                'keterangan' => 'Revisi jadwal',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('holidays', [
            'id' => $holiday->id,
            'nama_libur' => 'Libur Revisi',
        ]);
    }

    public function test_student_history_api()
    {
        $integration = \App\Models\ApiIntegration::create([
            'nama_aplikasi' => 'Test App',
            'api_key' => 'test-key-123',
            'is_active' => true,
        ]);

        $ay = AcademicYear::create([
            'nama' => '2025/2026 Ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'is_active' => true,
        ]);
        $class = SchoolClass::create(['nama_kelas' => 'X-IPA-1', 'academic_year_id' => $ay->id]);
        $student = Student::create([
            'class_id' => $class->id,
            'nis' => '99887766',
            'nama' => 'Siti Aminah',
            'rfid_uid' => '04X9Y8Z7',
            'status' => 'aktif',
        ]);

        $response = $this->withHeader('X-API-Key', 'test-key-123')
            ->getJson("/api/v1/students/{$student->id}/history?bulan=2026-07");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'student' => [
                    'id' => $student->id,
                    'nama' => 'Siti Aminah',
                ],
            ]);
    }
}
