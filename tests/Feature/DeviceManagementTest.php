<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeviceManagementTest extends TestCase
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

    public function test_admin_can_view_devices_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('devices.index'));
        $response->assertStatus(200);
        $response->assertSee('Mode 1: Kiosk Browser');
        $response->assertSee('Mode 2: Microcontroller IoT Box');
    }

    public function test_admin_can_create_kiosk_browser_device(): void
    {
        $response = $this->actingAs($this->admin)->post(route('devices.store'), [
            'nama_device' => 'Kiosk Laptop Gerbang',
            'tipe_device' => 'kiosk',
            'lokasi' => 'Lobby Utama',
            'token_device' => 'TOKEN_KIOSK_TEST_123',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('devices.index'));
        $this->assertDatabaseHas('devices', [
            'nama_device' => 'Kiosk Laptop Gerbang',
            'tipe_device' => 'kiosk',
        ]);
    }

    public function test_admin_can_create_microcontroller_iot_device(): void
    {
        $response = $this->actingAs($this->admin)->post(route('devices.store'), [
            'nama_device' => 'ESP32 Box Gate 2',
            'tipe_device' => 'microcontroller',
            'lokasi' => 'Gerbang Selatan',
            'token_device' => 'TOKEN_ESP32_TEST_999',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('devices.index'));
        $this->assertDatabaseHas('devices', [
            'nama_device' => 'ESP32 Box Gate 2',
            'tipe_device' => 'microcontroller',
        ]);
    }
}
