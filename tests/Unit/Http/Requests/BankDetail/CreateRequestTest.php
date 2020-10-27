<?php

namespace Tests\Unit\Http\Requests\BankDetail;

use App\Http\Requests\BankDetail\CreateRequest;
use Tests\TestCase;

/**
 * Class CreateRequestTest.
 *
 * @covers \App\Http\Requests\BankDetail\CreateRequest
 */
class CreateRequestTest extends TestCase
{
    /**
     * @var CreateRequest
     */
    protected $createRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->createRequest = new CreateRequest();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->createRequest);
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
