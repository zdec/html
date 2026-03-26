<?php

namespace Tests\Unit\Policies;

use App\Policies\ProductPolicy;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class ProductPolicyTest extends TestCase
{
    use CreatesAdminData;

    public function test_only_admin_can_manage_products(): void
    {
        $policy = new ProductPolicy();
        $admin = $this->createUser(true);
        $user = $this->createUser(false);
        $product = $this->createProduct();

        $this->assertTrue($policy->viewAny($admin));
        $this->assertTrue($policy->create($admin));
        $this->assertTrue($policy->update($admin, $product));
        $this->assertTrue($policy->delete($admin, $product));

        $this->assertFalse($policy->viewAny($user));
        $this->assertFalse($policy->create($user));
        $this->assertFalse($policy->update($user, $product));
        $this->assertFalse($policy->delete($user, $product));
    }
}
