<?php

namespace Tests\Feature\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Admin\TransactionController;
use Tests\TestCase;

/**
 * Class TransactionControllerTest.
 *
 * @covers \App\Http\Controllers\Web\Admin\TransactionController
 */
class TransactionControllerTest extends TestCase
{
    /**
     * @var TransactionController
     */
    protected $transactionController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->transactionController = new TransactionController();
        $this->app->instance(TransactionController::class, $this->transactionController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->transactionController);
    }
}
