<?php

namespace Tests\Unit\Models;

use App\Models\VerificationToken;
use Tests\TestCase;

/**
 * Class VerificationTokenTest.
 *
 * @covers \App\Models\VerificationToken
 */
class VerificationTokenTest extends TestCase
{
    /**
     * @var VerificationToken
     */
    protected $verificationToken;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->verificationToken = new VerificationToken();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->verificationToken);
    }
}
