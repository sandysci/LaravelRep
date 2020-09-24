<?php

namespace Tests\Unit\Services;

use App\Services\SavingCycleService;
use Tests\TestCase;

/**
 * Class SavingCycleServiceTest.
 *
 * @covers \App\Services\SavingCycleService
 */
class SavingCycleServiceTest extends TestCase
{
    /**
     * @var SavingCycleService
     */
    protected $savingCycleService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->savingCycleService = new SavingCycleService();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->savingCycleService);
    }

    public function testStore(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetAllUserSavingCycles(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetSavingCycles(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetAllSavingCycles(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testUpdateSavingCycleStatus(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testSendEmailToUser(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
