<?php

namespace Tests\Unit\Policies;

use App\Policies\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;
use Tests\Support\CreatesAdminData;

class UserPolicyTest extends TestCase
{
    use CreatesAdminData;

    public function test_only_admin_can_view_any_create_and_update_users(): void
    {
        $policy = new UserPolicy();
        $admin = $this->createUser(true);
        $user = $this->createUser(false);

        $this->assertTrue($policy->viewAny($admin));
        $this->assertTrue($policy->create($admin));
        $this->assertTrue($policy->update($admin, $user));

        $this->assertFalse($policy->viewAny($user));
        $this->assertFalse($policy->create($user));
        $this->assertFalse($policy->update($user, $admin));
    }

    public function test_user_policy_throws_when_trying_to_delete_self(): void
    {
        $policy = new UserPolicy();
        $admin = $this->createUser(true);

        $this->expectException(AuthorizationException::class);
        $policy->delete($admin, $admin);
    }
}
