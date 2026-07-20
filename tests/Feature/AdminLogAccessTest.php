<?php

namespace Tests\Feature;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLogAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_admin_cannot_open_the_admin_log(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        SuperAdmin::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'is_head' => false,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.logs'))
            ->assertForbidden();
    }

    public function test_primary_super_admin_can_open_the_admin_log(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        SuperAdmin::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'is_head' => true,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.logs'))
            ->assertOk()
            ->assertSee('Log Aktivitas');
    }
}
