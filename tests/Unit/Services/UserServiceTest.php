<?php

namespace Tests\Unit\Services;

use App\Services\MailService;
use App\Services\OtpService;
use App\Services\SmsService;
use App\Services\UserService;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

/**
 * Class UserServiceTest.
 *
 * @covers \App\Services\UserService
 */
class UserServiceTest extends TestCase
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var OtpService|Mock
     */
    protected $otpService;

    /**
     * @var MailService|Mock
     */
    protected $mailService;

    /**
     * @var SmsService|Mock
     */
    protected $smsService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->otpService = Mockery::mock(OtpService::class);
        $this->mailService = Mockery::mock(MailService::class);
        $this->smsService = Mockery::mock(SmsService::class);
        $this->userService = new UserService($this->otpService, $this->mailService, $this->smsService);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->userService);
        unset($this->otpService);
        unset($this->mailService);
        unset($this->smsService);
    }

    public function testLogin(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRegister(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testUpdate(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testFindOne(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testVerifyViaOTP(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testResendCode(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRequestPasswordResetToken(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
