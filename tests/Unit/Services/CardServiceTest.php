<?php

namespace Tests\Unit\Services;

use App\Models\Card;
use App\Services\CardService;
use App\Services\Payment\PaystackService;
use App\Services\TransactionService;
use App\Services\WalletService;
use Mockery;
use Mockery\Mock;
use ReflectionClass;
use Tests\TestCase;

/**
 * Class CardServiceTest.
 *
 * @covers \App\Services\CardService
 */
class CardServiceTest extends TestCase
{
    /**
     * @var CardService
     */
    protected $cardService;

    /**
     * @var PaystackService|Mock
     */
    protected $paystackService;

    /**
     * @var TransactionService|Mock
     */
    protected $transactionService;

    /**
     * @var WalletService|Mock
     */
    protected $walletService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->paystackService = Mockery::mock(PaystackService::class);
        $this->transactionService = Mockery::mock(TransactionService::class);
        $this->walletService = Mockery::mock(WalletService::class);
        $this->cardService = new CardService($this->paystackService, $this->transactionService, $this->walletService);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->cardService);
        unset($this->paystackService);
        unset($this->transactionService);
        unset($this->walletService);
    }

    public function testInitializeCardTrans(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPay(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testVerify(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testStore(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testStoreCard(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetCard(): void
    {
        $expected = Mockery::mock(Card::class);
        $property = (new ReflectionClass(CardService::class))
            ->getProperty('card');
        $property->setAccessible(true);
        $property->setValue($this->cardService, $expected);
        $this->assertSame($expected, $this->cardService->getCard());
    }

    public function testGetUserCards(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testChargeCard(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
