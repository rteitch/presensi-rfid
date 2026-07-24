<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogTest extends TestCase
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

    public function test_activity_is_logged_when_student_is_created_and_updated(): void
    {
        $class = SchoolClass::factory()->create();

        $student = Student::create([
            'nis' => '12345',
            'nama' => 'Siswa Test Log',
            'jenis_kelamin' => 'L',
            'class_id' => $class->id,
            'status' => 'aktif',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'model_type' => 'Student',
            'model_id' => $student->id,
        ]);

        $student->update(['nama' => 'Siswa Test Log Updated']);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'model_type' => 'Student',
            'model_id' => $student->id,
        ]);
    }

    public function test_admin_can_view_activity_logs_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('activity-logs.index'));
        $response->assertStatus(200);
    }
}
