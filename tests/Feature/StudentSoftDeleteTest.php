<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private SchoolClass $class;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->class = SchoolClass::factory()->create(['nama_kelas' => 'X-A']);
    }

    public function test_admin_can_soft_delete_student_and_view_in_trashed_page(): void
    {
        $student = Student::factory()->create([
            'class_id' => $this->class->id,
            'nis' => '112233',
            'nama' => 'Budi Terhapus',
        ]);

        // Soft Delete
        $response = $this->actingAs($this->admin)->delete(route('students.destroy', $student));
        $response->assertRedirect(route('students.index'));
        $this->assertSoftDeleted('students', ['id' => $student->id]);

        // View Trashed Page
        $trashedResponse = $this->actingAs($this->admin)->get(route('students.trashed'));
        $trashedResponse->assertOk();
        $trashedResponse->assertSee('Budi Terhapus');
    }

    public function test_admin_can_restore_soft_deleted_student(): void
    {
        $student = Student::factory()->create([
            'class_id' => $this->class->id,
            'nis' => '112244',
            'nama' => 'Siti Dipulihkan',
        ]);
        $student->delete();

        // Restore
        $response = $this->actingAs($this->admin)->post(route('students.restore', $student->id));
        $response->assertRedirect(route('students.trashed'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_can_force_delete_student_permanently(): void
    {
        $student = Student::factory()->create([
            'class_id' => $this->class->id,
            'nis' => '112255',
            'nama' => 'Eko Permanen',
        ]);
        $student->delete();

        // Force Delete
        $response = $this->actingAs($this->admin)->delete(route('students.force-delete', $student->id));
        $response->assertRedirect(route('students.trashed'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
