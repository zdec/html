<?php

namespace Tests\Unit\Policies;

use App\Policies\CustomerPolicy;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class CustomerPolicyTest extends TestCase
{
    use CreatesAdminData;

    public function test_only_admin_can_view_and_update_customers(): void
    {
        $policy = new CustomerPolicy();
        $admin = $this->createUser(true);
        $user = $this->createUser(false);
        $customer = $this->createCustomerFor();

        $this->assertTrue($policy->viewAny($admin));
        $this->assertTrue($policy->update($admin, $customer));
        $this->assertFalse($policy->viewAny($user));
        $this->assertFalse($policy->update($user, $customer));
    }
}
