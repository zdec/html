<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
        $this->forceInMemorySqlite();
        $this->bootstrapTestDatabase();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    protected function forceInMemorySqlite(): void
    {
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        Config::set('session.driver', 'array');
        Config::set('cache.default', 'array');
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');
        DB::reconnect('sqlite');
    }
}
