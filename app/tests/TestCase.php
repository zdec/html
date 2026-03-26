<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\Support\BootstrapsDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use BootstrapsDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapTestDatabase();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}
