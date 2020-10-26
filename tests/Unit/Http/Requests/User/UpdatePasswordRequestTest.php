<?php

namespace Tests\Unit\Http\Requests\User;

use App\Http\Requests\User\UpdatePasswordRequest;
use Tests\TestCase;

/**
 * Class UpdatePasswordRequestTest.
 *
 * @covers \App\Http\Requests\User\UpdatePasswordRequest
 */
class UpdatePasswordRequestTest extends TestCase
{
    /**
     * @var UpdatePasswordRequest
     */
    protected $updatePasswordRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->updatePasswordRequest = new UpdatePasswordRequest();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->updatePasswordRequest);
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
