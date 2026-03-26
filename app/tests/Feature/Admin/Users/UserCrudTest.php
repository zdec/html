<?php

namespace Tests\Feature\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class UserCrudTest extends TestCase
{
    use CreatesAdminData;

    public function test_admin_can_create_user(): void
    {
        $admin = $this->createUser(true);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@test.local',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_admin' => 0,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['email' => 'nuevo@test.local']);
    }

    public function test_admin_cannot_delete_user_with_id_one(): void
    {
        $admin = $this->createUser(true, ['id' => 99, 'email' => 'admin99@test.local']);
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Root',
            'email' => 'root@test.local',
            'password' => bcrypt('password123'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', ['user' => 1]), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422)->assertJson(['success' => false]);
        $this->assertDatabaseHas('users', ['id' => 1]);
    }

    public function test_admin_cannot_delete_himself(): void
    {
        $admin = $this->createUser(true);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', ['user' => $admin]));
        $response->assertForbidden();
    }
}
