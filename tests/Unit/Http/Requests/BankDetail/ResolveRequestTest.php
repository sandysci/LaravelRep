<?php

namespace Tests\Unit\Http\Requests\BankDetail;

use App\Http\Requests\BankDetail\ResolveRequest;
use Tests\TestCase;

/**
 * Class ResolveRequestTest.
 *
 * @covers \App\Http\Requests\BankDetail\ResolveRequest
 */
class ResolveRequestTest extends TestCase
{
    /**
     * @var ResolveRequest
     */
    protected $resolveRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->resolveRequest = new ResolveRequest();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->resolveRequest);
    }

    public function testAuthorize(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRules(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
