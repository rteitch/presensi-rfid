<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClassControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->guru = User::factory()->create();
        $this->guru->assignRole('guru');
    }

    public function test_admin_can_view_classes_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('classes.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_class(): void
    {
        $year = AcademicYear::create([
            'nama' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2027-06-30',
        ]);

        $response = $this->actingAs($this->admin)->post(route('classes.store'), [
            'nama_kelas' => 'X IPA 1',
            'academic_year_id' => $year->id,
            'wali_kelas_id' => $this->guru->id,
        ]);

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('classes', ['nama_kelas' => 'X IPA 1']);
    }

    public function test_admin_can_view_class_details(): void
    {
        $class = SchoolClass::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('classes.show', $class));
        $response->assertStatus(200);
    }
}
