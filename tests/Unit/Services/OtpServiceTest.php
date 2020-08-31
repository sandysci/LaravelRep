<?php

namespace Tests\Unit\Services;

use App\Models\Otp;
use App\Services\OtpService;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

/**
 * Class OtpServiceTest.
 *
 * @covers \App\Services\OtpService
 */
class OtpServiceTest extends TestCase
{
    /**
     * @var OtpService
     */
    protected $otpService;

    /**
     * @var Otp|Mock
     */
    protected $otp;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->otp = Mockery::mock(Otp::class);
        $this->otpService = new OtpService($this->otp);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->otpService);
        unset($this->otp);
    }

    public function testCreate(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testValidate(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGeneratePin(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
