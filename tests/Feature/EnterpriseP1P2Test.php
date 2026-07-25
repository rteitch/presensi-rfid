<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseP1P2Test extends TestCase
{
    use RefreshDatabase;

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
        Carbon::setTestNow('2026-07-27'); // Monday (school day)

        $ay = \App\Models\AcademicYear::create([
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
}
