<?php

namespace Tests\Unit\Http\Requests\User;

use App\Http\Requests\User\VerificationRequest;
use Tests\TestCase;

/**
 * Class VerificationRequestTest.
 *
 * @covers \App\Http\Requests\User\VerificationRequest
 */
class VerificationRequestTest extends TestCase
{
    /**
     * @var VerificationRequest
     */
    protected $verificationRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->verificationRequest = new VerificationRequest();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->verificationRequest);
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
