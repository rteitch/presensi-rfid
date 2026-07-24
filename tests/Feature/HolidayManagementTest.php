<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Holiday;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HolidayManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_create_holiday(): void
    {
        $response = $this->actingAs($this->admin)->post(route('holidays.store'), [
            'nama_libur' => 'Libur Nasional',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
            'keterangan' => 'Ujian Akhir Semester',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('holidays', ['nama_libur' => 'Libur Nasional']);
    }

    public function test_rfid_scan_blocked_on_holiday(): void
    {
        $today = now()->toDateString();
        Holiday::create([
            'nama_libur' => 'Libur Nasional',
            'tanggal_mulai' => $today,
            'tanggal_selesai' => $today,
        ]);

        $class = SchoolClass::factory()->create();
        $student = Student::factory()->create(['rfid_uid' => 'HOLIDAY_UID', 'class_id' => $class->id]);
        $device = Device::create(['nama_device' => 'Main Gate', 'lokasi' => 'Gate 1', 'token_device' => 'test_token', 'is_active' => true]);

        $response = $this->withHeader('X-Device-Token', 'test_token')
            ->postJson('/api/rfid/scan', ['rfid_uid' => 'HOLIDAY_UID']);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Hari ini adalah Hari Libur Sekolah.',
            ]);
    }
}
