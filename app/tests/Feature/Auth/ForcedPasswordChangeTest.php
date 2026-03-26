<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Tests\Support\CreatesAdminData;
use Tests\TestCase;

class ForcedPasswordChangeTest extends TestCase
{
    use CreatesAdminData;

    public function test_user_with_flag_is_redirected_to_force_change_after_login(): void
    {
        $user = $this->createUser(false, [
            'email' => 'cliente-force@test.local',
            'password' => Hash::make('temporal123'),
            'must_change_password' => true,
            'password_temp_created_at' => now(),
        ]);
        $this->createCustomerFor($user);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'temporal123',
        ]);

        $response->assertRedirect(route('password.force-change.edit'));
    }

    public function test_forced_password_update_clears_flag_and_redirects_to_admin(): void
    {
        $user = $this->createUser(false, [
            'password' => Hash::make('temporal123'),
            'must_change_password' => true,
            'password_temp_created_at' => now(),
        ]);
        $this->createCustomerFor($user);

        $response = $this->actingAs($user)->put(route('password.force-change.update'), [
            'password' => 'nuevaPassword123',
            'password_confirmation' => 'nuevaPassword123',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'must_change_password' => false,
        ]);
    }
}
