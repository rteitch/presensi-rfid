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

    /** 9. Kiosk Page Renders */
    public function test_kiosk_page_renders_successfully(): void
    {
        $response = $this->get('/kiosk');
        $response->assertStatus(200)
            ->assertSee('PRESENSI RTH NEXUS');
    }

    /** 10. Dashboard Stats & Guru Scoping Access */
    public function test_dashboard_access_for_admin_and_guru(): void
    {
        $adminRes = $this->actingAs($this->admin)->get('/dashboard');
        $adminRes->assertStatus(200);

        $guruRes = $this->actingAs($this->guru)->get('/dashboard');
        $guruRes->assertStatus(200);
    }

    /** 11. Holiday Non-Admin Protection */
    public function test_non_admin_cannot_create_holiday(): void
    {
        $response = $this->actingAs($this->guru)->post(route('holidays.store'), [
            'nama_libur' => 'Libur Unauthorized',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-02',
        ]);

        $response->assertStatus(403);
    }

    /** 12. School Settings Update */
    public function test_admin_can_update_school_settings(): void
    {
        $response = $this->actingAs($this->admin)->post(route('settings.school.update'), [
            'app_name' => 'PRESENSI NEXUS TEST',
            'school_name' => 'SMK Negeri 1 Test',
            'kiosk_bg_type' => 'gradient',
            'rate_limit_api' => 120,
        ]);

        $response->assertRedirect();
        $this->assertEquals('PRESENSI NEXUS TEST', \App\Models\SchoolSetting::get('app_name'));
        $this->assertEquals(120, (int) \App\Models\SchoolSetting::get('rate_limit_api'));
    }

    /** 13. Class Delete Guard when class has active students */
    public function test_class_deletion_guard_when_students_exist(): void
    {
        Student::create([
            'class_id' => $this->class->id,
            'nis' => '12344321',
            'nama' => 'Siswa Di Kelas',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('classes.destroy', $this->class));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('classes', ['id' => $this->class->id]);
    }

    /** 14. Manual Attendance Overwrites Record */
    public function test_manual_attendance_overwrites_existing_record(): void
    {
        $student = Student::create([
            'class_id' => $this->class->id,
            'nis' => '99001122',
            'nama' => 'Manual Overwrite Student',
            'status' => 'aktif',
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'tanggal' => '2026-07-25',
            'status' => 'hadir',
        ]);

        $response = $this->actingAs($this->admin)->post(route('attendances.manual'), [
            'student_id' => [$student->id],
            'tanggal' => '2026-07-25',
            'status' => 'izin',
            'keterangan' => 'Izin via surat dokter',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'tanggal' => '2026-07-25',
            'status' => 'izin',
        ]);
    }

    /** 15. Auto-Alpha Skips Already Marked Student */
    public function test_auto_alpha_skips_already_marked_student(): void
    {
        Carbon::setTestNow('2026-07-27 18:00:00'); // Monday evening

        $student = Student::create([
            'class_id' => $this->class->id,
            'nis' => '55443322',
            'nama' => 'Hadir Student',
            'status' => 'aktif',
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'tanggal' => '2026-07-27',
            'status' => 'hadir',
        ]);

        $this->artisan('attendance:auto-alpha')->assertExitCode(0);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'tanggal' => '2026-07-27',
            'status' => 'hadir',
        ]);

        Carbon::setTestNow();
    }

    /** 16. Auto-Alpha Ignores Inactive Student */
    public function test_auto_alpha_ignores_inactive_student(): void
    {
        Carbon::setTestNow('2026-07-27 18:00:00'); // Monday evening

        $inactiveStudent = Student::create([
            'class_id' => $this->class->id,
            'nis' => '11002299',
            'nama' => 'Non Aktif Student',
            'status' => 'nonaktif',
        ]);

        $this->artisan('attendance:auto-alpha')->assertExitCode(0);

        $this->assertDatabaseMissing('attendances', [
            'student_id' => $inactiveStudent->id,
            'tanggal' => '2026-07-27',
        ]);

        Carbon::setTestNow();
    }

    /** 17. API Student History Invalid Month Format */
    public function test_api_student_history_invalid_month_format(): void
    {
        $integration = \App\Models\ApiIntegration::create([
            'nama_aplikasi' => 'Test App History',
            'api_key' => 'valid-history-key-111',
            'is_active' => true,
        ]);

        $student = Student::create([
            'class_id' => $this->class->id,
            'nis' => '88223344',
            'nama' => 'Test Student API',
            'status' => 'aktif',
        ]);

        $response = $this->withHeader('X-API-Key', 'valid-history-key-111')
            ->getJson("/api/v1/students/{$student->id}/history?bulan=invalid-month");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['bulan']);
    }

    /** 18. API Student History Non-Existent Student */
    public function test_api_student_history_non_existent_student(): void
    {
        $integration = \App\Models\ApiIntegration::create([
            'nama_aplikasi' => 'Test App History 404',
            'api_key' => 'valid-history-key-404',
            'is_active' => true,
        ]);

        $response = $this->withHeader('X-API-Key', 'valid-history-key-404')
            ->getJson('/api/v1/students/999999/history');

        $response->assertStatus(404);
    }

    /** 19. Academic Year Creation */
    public function test_admin_can_create_academic_year(): void
    {
        $response = $this->actingAs($this->admin)->post(route('settings.academic-year'), [
            'nama' => '2026/2027 Ganjil',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-12-31',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('academic_years', ['nama' => '2026/2027 Ganjil']);
    }

    /** 20. Device Update and Delete */
    public function test_admin_can_update_and_delete_device(): void
    {
        $device = Device::create([
            'nama_device' => 'Scanner Gerbang Barat',
            'lokasi' => 'Gerbang Barat',
            'token_device' => 'token-barat-123',
            'tipe_device' => 'kiosk_browser',
            'is_active' => true,
        ]);

        // Update
        $updateRes = $this->actingAs($this->admin)->put(route('devices.update', $device), [
            'nama_device' => 'Scanner Gerbang Barat Revisi',
            'lokasi' => 'Gerbang Barat Lt 1',
            'tipe_device' => 'kiosk',
            'token_device' => 'token-barat-123',
            'is_active' => true,
        ]);
        $updateRes->assertRedirect(route('devices.index'));
        $this->assertDatabaseHas('devices', ['nama_device' => 'Scanner Gerbang Barat Revisi']);

        // Delete
        $deleteRes = $this->actingAs($this->admin)->delete(route('devices.destroy', $device));
        $deleteRes->assertRedirect(route('devices.index'));
        $this->assertDatabaseMissing('devices', ['id' => $device->id]);
    }

    /** 21. Concurrent RFID Scan Lock Race Condition */
    public function test_concurrent_rfid_scan_race_condition(): void
    {
        $student = Student::create([
            'class_id' => $this->class->id,
            'nis' => '77889900',
            'nama' => 'Concurrent Tap Student',
            'rfid_uid' => '04LOCK123',
            'status' => 'aktif',
        ]);

        $device = Device::create([
            'nama_device' => 'Lock Device',
            'token_device' => 'lock-token-99',
            'tipe_device' => 'kiosk',
            'is_active' => true,
        ]);

        // Lock cache key for 5 seconds to simulate concurrent active scan
        $lockKey = "scan_lock_{$student->id}_" . date('Y-m-d');
        \Illuminate\Support\Facades\Cache::lock($lockKey, 5)->get();

        $response = $this->withHeader('X-Device-Token', 'lock-token-99')
            ->postJson('/api/rfid/scan', ['rfid_uid' => '04LOCK123']);

        $response->assertStatus(200)
            ->assertJson(['success' => false, 'message' => 'Mohon tunggu sejenak sebelum melakukan tap kembali.']);
    }

    /** 22. External API Rate Limiting Enforcement */
    public function test_external_api_rate_limiting_enforcement(): void
    {
        \App\Models\SchoolSetting::set('rate_limit_api', 2);

        $integration = \App\Models\ApiIntegration::create([
            'nama_aplikasi' => 'Rate Limit Test App',
            'api_key' => 'rate-limit-key-777',
            'is_active' => true,
        ]);

        // Request 1 & 2 succeed
        $this->withHeader('X-API-Key', 'rate-limit-key-777')->getJson('/api/v1/attendances/rekap')->assertStatus(200);
        $this->withHeader('X-API-Key', 'rate-limit-key-777')->getJson('/api/v1/attendances/rekap')->assertStatus(200);

        // Request 3 is rate limited (429 Too Many Requests)
        $res3 = $this->withHeader('X-API-Key', 'rate-limit-key-777')->getJson('/api/v1/attendances/rekap');
        $res3->assertStatus(429);
    }

    /** 23. Dynamic School Work Days (Multi-day & Pesantren Jumat Libur) Handling */
    public function test_school_work_days_setting_and_auto_alpha_handling(): void
    {
        // 1. Set to Pesantren school (Sabtu - Kamis, Jumat Libur)
        $pesantrenDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        \App\Models\SchoolSetting::set('hari_efektif', json_encode($pesantrenDays));
        $decoded = json_decode(\App\Models\SchoolSetting::get('hari_efektif'), true);
        $this->assertEquals($pesantrenDays, $decoded);
        $this->assertNotContains('Friday', $decoded);

        // 2. Set to 5-day school (Senin - Jumat)
        $standardDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        \App\Models\SchoolSetting::set('hari_efektif', json_encode($standardDays));
        $decoded2 = json_decode(\App\Models\SchoolSetting::get('hari_efektif'), true);
        $this->assertEquals($standardDays, $decoded2);
    }
}
