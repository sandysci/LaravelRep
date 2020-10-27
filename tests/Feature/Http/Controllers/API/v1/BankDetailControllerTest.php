<?php

namespace Tests\Feature\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\BankDetailController;
use Tests\TestCase;

/**
 * Class BankDetailControllerTest.
 *
 * @covers \App\Http\Controllers\API\v1\BankDetailController
 */
class BankDetailControllerTest extends TestCase
{
    /**
     * @var BankDetailController
     */
    protected $bankDetailController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->bankDetailController = new BankDetailController();
        $this->app->instance(BankDetailController::class, $this->bankDetailController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->bankDetailController);
    }
}
