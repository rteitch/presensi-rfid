<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_teacher_index_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('teachers.index'));

        $response->assertOk();
    }

    public function test_teacher_create_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('teachers.create'));

        $response->assertOk();
    }

    public function test_teacher_can_be_created(): void
    {
        $data = [
            'nip' => '198501012010011001',
            'nama' => 'Guru Test',
            'email' => 'guru@test.com',
            'no_hp' => '081234567890',
            'mata_pelajaran' => 'Matematika',
            'status' => 'aktif',
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post(route('teachers.store'), $data);

        $response->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('teachers', ['nip' => '198501012010011001', 'nama' => 'Guru Test']);
    }

    public function test_teacher_nip_is_required(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('teachers.store'), [
                'nip' => '',
                'nama' => 'Guru Test',
                'status' => 'aktif',
            ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_teacher_nip_must_be_unique(): void
    {
        Teacher::factory()->create(['nip' => '1234567890']);

        $response = $this
            ->actingAs($this->admin)
            ->post(route('teachers.store'), [
                'nip' => '1234567890',
                'nama' => 'Guru Test',
                'status' => 'aktif',
            ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_teacher_edit_page_is_displayed(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->get(route('teachers.edit', $teacher));

        $response->assertOk();
    }

    public function test_teacher_can_be_updated(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->put(route('teachers.update', $teacher), [
                'nip' => $teacher->nip,
                'nama' => 'Guru Updated',
                'status' => 'aktif',
            ]);

        $response->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('teachers', ['id' => $teacher->id, 'nama' => 'Guru Updated']);
    }

    public function test_teacher_can_be_deleted(): void
    {
        $teacher = Teacher::factory()->create();

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('teachers.destroy', $teacher));

        $response->assertRedirect(route('teachers.index'));
        $this->assertSoftDeleted('teachers', ['id' => $teacher->id]);
    }

    public function test_teacher_search_works(): void
    {
        Teacher::factory()->create(['nama' => 'Ahmad Fauzan', 'nip' => '1111111111']);
        Teacher::factory()->create(['nama' => 'Siti Nurhaliza', 'nip' => '2222222222']);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('teachers.index', ['search' => 'Ahmad']));

        $response->assertOk();
        $response->assertSee('Ahmad Fauzan');
        $response->assertDontSee('Siti Nurhaliza');
    }

    public function test_non_admin_cannot_access_teacher_crud(): void
    {
        $guru = User::factory()->create();
        $guru->assignRole('guru');

        $response = $this
            ->actingAs($guru)
            ->get(route('teachers.index'));

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('teachers.index'));

        $response->assertRedirect(route('login'));
    }
}
