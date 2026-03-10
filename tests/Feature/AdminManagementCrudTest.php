<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_admin_or_mentor_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.management.store'), [
            'name' => 'Mentor Baru',
            'email' => 'mentorbaru@example.com',
            'role' => 'mentor',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.management.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Mentor Baru',
            'email' => 'mentorbaru@example.com',
            'role' => 'mentor',
        ]);
    }

    public function test_admin_can_update_admin_or_mentor_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $target = User::factory()->create([
            'role' => 'mentor',
            'name' => 'Nama Lama',
            'email' => 'nama-lama@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.management.update', $target), [
            'name' => 'Nama Baru',
            'email' => 'nama-baru@example.com',
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.management.index'));
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Nama Baru',
            'email' => 'nama-baru@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_other_admin_or_mentor_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $target = User::factory()->create([
            'role' => 'mentor',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.management.destroy', $target));

        $response->assertRedirect(route('admin.management.index'));
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.management.destroy', $admin));

        $response->assertRedirect(route('admin.management.index'));
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
