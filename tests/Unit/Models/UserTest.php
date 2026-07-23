<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles_can_access_filament_panel(): void
    {
        $panel = \Mockery::mock(Panel::class);

        $admin = User::factory()->make(['role' => 'admin']);
        $cashier = User::factory()->make(['role' => 'cashier']);
        $kitchen = User::factory()->make(['role' => 'kitchen']);
        $customer = User::factory()->make(['role' => 'customer']);

        $this->assertTrue($admin->canAccessPanel($panel));
        $this->assertTrue($cashier->canAccessPanel($panel));
        $this->assertTrue($kitchen->canAccessPanel($panel));
        $this->assertFalse($customer->canAccessPanel($panel));
    }
}
