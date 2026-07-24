<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentImportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_import_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('students.import'));

        $response->assertOk();
        $response->assertSee('Import Data Siswa');
    }

    public function test_import_requires_file(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('students.import-store'));

        $response->assertSessionHasErrors('file');
    }

    public function test_import_rejects_invalid_file_type(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('test.txt', 'not an excel file');

        $response = $this
            ->actingAs($this->admin)
            ->post(route('students.import-store'), [
                'file' => $file,
            ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_export_returns_excel_file(): void
    {
        $kelas = SchoolClass::factory()->create();
        Student::factory()->count(3)->create(['class_id' => $kelas->id]);

        $response = $this
            ->actingAs($this->admin)
            ->get(route('students.export'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition');
    }

    public function test_template_returns_excel_file(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->get(route('students.template'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition');
    }

    public function test_non_admin_cannot_import(): void
    {
        $guru = User::factory()->create();
        $guru->assignRole('guru');

        $response = $this
            ->actingAs($guru)
            ->get(route('students.import'));

        $response->assertForbidden();
    }
}
