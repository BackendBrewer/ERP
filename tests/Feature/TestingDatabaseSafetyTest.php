<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestingDatabaseSafetyTest extends TestCase
{
    public function test_database_is_isolated_for_testing(): void
    {
        $this->assertSame('testing', app()->environment());

        $this->assertSame(
            'sqlite',
            config('database.default')
        );

        $this->assertSame(
            ':memory:',
            config('database.connections.sqlite.database')
        );

        $this->assertContains(
            config('database.connections.sqlite.url'),
            [null, '']
        );
    }
}
