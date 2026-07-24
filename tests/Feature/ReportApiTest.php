<?php

namespace Tests\Feature;

use App\Models\ApiIntegration;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    protected ApiIntegration $integration;
    protected SchoolClass $class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->integration = ApiIntegration::create([
            'nama_aplikasi' => 'SIM Sekolah Test',
            'deskripsi' => 'Test integrasi otomatis',
            'api_key' => 'RTHAPI-TESTKEY12345ABCDE',
            'is_active' => true,
        ]);

        $year = \App\Models\AcademicYear::create([
            'nama' => '2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);

        $this->class = SchoolClass::create([
            'nama_kelas' => 'X-A',
            'academic_year_id' => $year->id,
        ]);
    }

    public function test_external_app_can_fetch_rekap_presensi_with_valid_api_key(): void
    {
        Student::create([
            'nis' => '99001',
            'nama' => 'Siswa Test API',
            'class_id' => $this->class->id,
            'status' => 'aktif',
        ]);

        $response = $this->withHeader('X-API-Key', 'RTHAPI-TESTKEY12345ABCDE')
            ->getJson('/api/v1/attendances/rekap?bulan=' . now()->format('Y-m'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'periode_bulan',
            'total_siswa',
            'data' => [
                '*' => [
                    'student_id', 'nis', 'nama', 'kelas',
                    'rekap' => ['hadir', 'terlambat', 'izin', 'sakit', 'alpha', 'total_kehadiran'],
                ],
            ],
        ]);
    }

    public function test_api_integration_fails_without_valid_api_key(): void
    {
        $response = $this->getJson('/api/v1/attendances/rekap');
        $response->assertStatus(401);
    }

    public function test_api_integration_fails_with_inactive_integration(): void
    {
        $this->integration->update(['is_active' => false]);

        $response = $this->withHeader('X-API-Key', 'RTHAPI-TESTKEY12345ABCDE')
            ->getJson('/api/v1/attendances/rekap');

        $response->assertStatus(401);
    }

    public function test_api_key_last_used_at_is_updated_on_successful_request(): void
    {
        $this->assertNull($this->integration->last_used_at);

        $this->withHeader('X-API-Key', 'RTHAPI-TESTKEY12345ABCDE')
            ->getJson('/api/v1/attendances/rekap');

        $this->assertNotNull($this->integration->fresh()->last_used_at);
    }
}
