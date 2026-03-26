<?php

namespace Tests\Feature\Admin\Customers;

use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class CustomerManagementTest extends TestCase
{
    use CreatesAdminData;

    public function test_admin_can_update_customer(): void
    {
        $admin = $this->createUser(true);
        $customer = $this->createCustomerFor(null, [
            'email' => 'cliente@test.local',
            'name' => 'Cliente Original',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.customers.update', $customer), [
            'name' => 'Cliente Editado',
            'email' => 'cliente@test.local',
            'phone' => '3110001122',
            'address' => 'Cra 10',
            'city' => 'Medellin',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Cliente Editado',
            'city' => 'Medellin',
        ]);
    }

    public function test_non_admin_cannot_access_customers_index(): void
    {
        $user = $this->createUser(false);
        $response = $this->actingAs($user)->get(route('admin.customers.index'));
        $response->assertForbidden();
    }
}
