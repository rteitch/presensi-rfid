<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);
    }

    public function test_admin_can_view_users_index(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }

    public function test_admin_can_create_new_admin_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'New Admin User',
            'email' => 'newadmin@school.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newadmin@school.id']);

        $newUser = User::where('email', 'newadmin@school.id')->first();
        $this->assertTrue($newUser->hasRole('admin'));

        // Test login with created password (verifies no double hashing bug)
        $this->post('/logout');
        session(['captcha_answer' => 10]);
        $loginResponse = $this->post('/login', [
            'email' => 'newadmin@school.id',
            'password' => 'password123',
            'captcha_answer' => 10,
        ]);
        $this->assertAuthenticatedAs($newUser);
    }

    public function test_last_admin_cannot_delete_their_account(): void
    {
        $lastAdmin = User::factory()->create(['password' => bcrypt('password123')]);
        $lastAdmin->assignRole('admin');

        $response = $this->actingAs($lastAdmin)->delete(route('profile.destroy'), [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('password', null, 'userDeletion');
        $this->assertDatabaseHas('users', ['id' => $lastAdmin->id]);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $guru = User::factory()->create();
        $guru->assignRole('guru');

        $response = $this->actingAs($guru)->get(route('users.index'));

        $response->assertStatus(403);
    }
}
