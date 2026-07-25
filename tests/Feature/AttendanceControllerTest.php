<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guru;
    protected SchoolClass $classA;
    protected SchoolClass $classB;
    protected Student $studentA;
    protected Student $studentB;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->guru = User::factory()->create();
        $this->guru->assignRole('guru');

        $this->classA = SchoolClass::factory()->create(['wali_kelas_id' => $this->guru->id]);
        $this->classB = SchoolClass::factory()->create();

        $this->studentA = Student::factory()->create(['class_id' => $this->classA->id]);
        $this->studentB = Student::factory()->create(['class_id' => $this->classB->id]);
    }

    public function test_admin_can_store_manual_attendance_for_any_student(): void
    {
        $response = $this->actingAs($this->admin)->post(route('attendances.manual'), [
            'student_id' => [$this->studentB->id],
            'tanggal' => now()->toDateString(),
            'status' => 'hadir',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'student_id' => $this->studentB->id,
            'status' => 'hadir',
        ]);
    }

    public function test_guru_can_store_attendance_for_managed_student(): void
    {
        $response = $this->actingAs($this->guru)->post(route('attendances.manual'), [
            'student_id' => [$this->studentA->id],
            'tanggal' => now()->toDateString(),
            'status' => 'hadir',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'student_id' => $this->studentA->id,
            'status' => 'hadir',
        ]);
    }

    public function test_guru_cannot_store_attendance_for_unmanaged_student_idor_check(): void
    {
        // Guru submits with student outside their managed class — should be silently skipped, not saved
        $response = $this->actingAs($this->guru)->post(route('attendances.manual'), [
            'student_id' => [$this->studentB->id],
            'tanggal' => now()->toDateString(),
            'status' => 'hadir',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('attendances', [
            'student_id' => $this->studentB->id,
            'tanggal' => now()->toDateString(),
        ]);
    }
}
