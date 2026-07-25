<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Device;
use App\Models\Holiday;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ComprehensiveIstqbCoverageTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guru;
    protected AcademicYear $year;
    protected SchoolClass $class;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->guru = User::factory()->create();
        $this->guru->assignRole('guru');

        $this->year = AcademicYear::create([
            'nama' => '2025/2026 Ganjil',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'is_active' => true,
        ]);

        $this->class = SchoolClass::create([
            'nama_kelas' => 'X-IPA-1',
            'academic_year_id' => $this->year->id,
            'wali_kelas_id' => $this->guru->id,
        ]);
    }

    /** 1. Guru IDOR Student Show Access */
    public function test_guru_cannot_view_unmanaged_student_details(): void
    {
        $otherClass = SchoolClass::create([
            'nama_kelas' => 'XI-IPS-2',
            'academic_year_id' => $this->year->id,
        ]);

        $otherStudent = Student::create([
            'class_id' => $otherClass->id,
            'nis' => '88811122',
            'nama' => 'Siswa Kelas Lain',
            'rfid_uid' => '04A1B299',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->guru)->get(route('students.show', $otherStudent));
        $response->assertStatus(403);
    }

    /** 2. Auto-Alpha Skips Before Jam Pulang */
    public function test_auto_alpha_skips_before_jam_pulang(): void
    {
        Carbon::setTestNow('2026-07-27 10:00:00'); // 10 AM (before 15:00)

        AttendanceSetting::create([
            'academic_year_id' => $this->year->id,
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '15:00:00',
            'toleransi_menit' => 15,
        ]);

        Student::create([
            'class_id' => $this->class->id,
            'nis' => '11224455',
            'nama' => 'Student Early Test',
            'rfid_uid' => '04Z1Z2Z3',
            'status' => 'aktif',
        ]);

        $this->artisan('attendance:auto-alpha')
            ->expectsOutputToContain('belum melewati jam pulang')
            ->assertExitCode(0);

        Carbon::setTestNow();
    }

    /** 3. Student CRUD Operations */
    public function test_admin_can_create_update_and_delete_student(): void
    {
        // Create
        $response = $this->actingAs($this->admin)->post(route('students.store'), [
            'class_id' => $this->class->id,
            'nis' => '77665544',
            'nama' => 'Andi Wijaya',
            'rfid_uid' => '04X8Y7Z6',
            'nama_ortu' => 'Bapak Andi',
            'no_hp_ortu' => '081234567890',
            'status' => 'aktif',
        ]);
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['nis' => '77665544']);

        $student = Student::where('nis', '77665544')->first();

        // Update
        $response = $this->actingAs($this->admin)->put(route('students.update', $student), [
            'class_id' => $this->class->id,
            'nis' => '77665544',
            'nama' => 'Andi Wijaya Updated',
            'rfid_uid' => '04X8Y7Z6',
            'nama_ortu' => 'Bapak Andi',
            'no_hp_ortu' => '081234567890',
            'status' => 'aktif',
        ]);
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['nama' => 'Andi Wijaya Updated']);

        // Delete (SoftDeletes)
        $response = $this->actingAs($this->admin)->delete(route('students.destroy', $student));
        $response->assertRedirect(route('students.index'));
        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }

    /** 4. Class CRUD Operations & Protection */
    public function test_admin_can_update_class(): void
    {
        $response = $this->actingAs($this->admin)->put(route('classes.update', $this->class), [
            'nama_kelas' => 'X-IPA-1 RTH',
            'academic_year_id' => $this->year->id,
            'wali_kelas_id' => $this->guru->id,
        ]);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('classes', ['nama_kelas' => 'X-IPA-1 RTH']);
    }

    /** 5. Device Token Regeneration */
    public function test_admin_can_regenerate_device_token(): void
    {
        $device = Device::create([
            'nama_device' => 'Kiosk Main Gate',
            'lokasi' => 'Gerbang Depan',
            'token_device' => 'old-secret-token',
            'tipe_device' => 'kiosk_browser',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('devices.regenerate', $device));

        $response->assertRedirect();

        $device->refresh();
        $this->assertNotEquals('old-secret-token', $device->token_device);
    }

    /** 6. Holiday Delete & Protection */
    public function test_admin_can_delete_holiday(): void
    {
        $holiday = Holiday::create([
            'nama_libur' => 'Libur Sementara',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-02',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('holidays.destroy', $holiday));
        $response->assertRedirect();
        $this->assertDatabaseMissing('holidays', ['id' => $holiday->id]);
    }

    /** 7. Export Class PDF Download Response */
    public function test_export_class_pdf_returns_pdf_download(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('classes.export-pdf', ['class' => $this->class->id, 'bulan' => '2026-07']));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /** 8. External API Invalid Class Validation */
    public function test_external_api_rejects_invalid_class_id(): void
    {
        $integration = \App\Models\ApiIntegration::create([
            'nama_aplikasi' => 'Test App Validation',
            'api_key' => 'valid-key-999',
            'is_active' => true,
        ]);

        $response = $this->withHeader('X-API-Key', 'valid-key-999')
            ->getJson('/api/v1/attendances/rekap?class_id=invalid_string');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['class_id']);
    }
}
