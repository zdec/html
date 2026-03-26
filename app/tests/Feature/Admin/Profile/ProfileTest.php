<?php

namespace Tests\Feature\Admin\Profile;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class ProfileTest extends TestCase
{
    use CreatesAdminData;

    public function test_user_can_update_profile_and_customer_data(): void
    {
        $user = $this->createUser(false, [
            'email' => 'perfil@test.local',
            'password' => bcrypt('password123'),
        ]);
        $this->createCustomerFor($user, ['city' => 'Cali']);

        $response = $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => 'Perfil Nuevo',
            'email' => 'perfil-nuevo@test.local',
            'phone' => '3003334455',
            'address' => 'Av 123',
            'city' => 'Bogota',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'perfil-nuevo@test.local']);
        $this->assertDatabaseHas('customers', ['user_id' => $user->id, 'city' => 'Bogota']);
    }

    public function test_user_can_change_password_with_current_password(): void
    {
        $user = $this->createUser(false, ['password' => bcrypt('password123')]);

        $response = $this->actingAs($user)->put(route('admin.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}
