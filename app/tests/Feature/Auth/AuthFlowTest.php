<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class AuthFlowTest extends TestCase
{
    use CreatesAdminData;

    public function test_user_can_login_and_is_redirected_to_admin_orders(): void
    {
        $user = $this->createUser(true, [
            'email' => 'admin@test.local',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.local',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->createUser(true, [
            'email' => 'admin@test.local',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@test.local',
            'password' => 'bad-pass',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_admin_only_product_routes(): void
    {
        $user = $this->createUser(false);
        $this->actingAs($user);

        $response = $this->get(route('admin.products.index'));
        $response->assertForbidden();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
