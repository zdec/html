<?php

namespace Tests\Unit\Policies;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class OrderPolicyTest extends TestCase
{
    use CreatesAdminData;

    public function test_admin_can_view_and_update_any_order(): void
    {
        $policy = new OrderPolicy();
        $admin = $this->createUser(true);
        $customer = $this->createCustomerFor();
        $order = $this->createOrder($customer);

        $this->assertTrue($policy->view($admin, $order));
        $this->assertTrue($policy->update($admin, $order));
    }

    public function test_customer_can_only_view_own_order(): void
    {
        $policy = new OrderPolicy();
        $user = $this->createUser(false);
        $customer = $this->createCustomerFor($user);
        $otherCustomer = $this->createCustomerFor();

        $ownOrder = $this->createOrder($customer);
        $otherOrder = $this->createOrder($otherCustomer);

        $this->assertTrue($policy->view($user, $ownOrder));
        $this->assertFalse($policy->view($user, $otherOrder));
    }

    public function test_only_admin_can_delete_and_only_in_draft_or_pedido(): void
    {
        $policy = new OrderPolicy();
        $admin = $this->createUser(true);
        $user = $this->createUser(false);
        $customer = $this->createCustomerFor();

        $pedido = $this->createOrder($customer, [], ['status' => Order::STATUS_PEDIDO]);
        $venta = $this->createOrder($customer, [], ['status' => Order::STATUS_VENTA]);

        $this->assertTrue($policy->delete($admin, $pedido));
        $this->assertFalse($policy->delete($admin, $venta));
        $this->assertFalse($policy->delete($user, $pedido));
    }
}
