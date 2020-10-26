<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ArcanedevLoggerMiddleware;
use Tests\TestCase;

/**
 * Class ArcanedevLoggerMiddlewareTest.
 *
 * @covers \App\Http\Middleware\ArcanedevLoggerMiddleware
 */
class ArcanedevLoggerMiddlewareTest extends TestCase
{
    /**
     * @var ArcanedevLoggerMiddleware
     */
    protected $arcanedevLoggerMiddleware;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->arcanedevLoggerMiddleware = new ArcanedevLoggerMiddleware();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->arcanedevLoggerMiddleware);
    }

    public function testHandle(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
