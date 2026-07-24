<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportControllerTest extends TestCase
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

    public function test_admin_can_access_reports_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_rekap_report(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.rekap'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_private_leaderboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.leaderboard'));
        $response->assertStatus(200);
    }

    public function test_anyone_can_access_public_leaderboard(): void
    {
        $response = $this->get(route('public.leaderboard'));
        $response->assertStatus(200);
    }
}
