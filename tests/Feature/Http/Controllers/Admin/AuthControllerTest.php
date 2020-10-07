<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AuthController;
use Tests\TestCase;

/**
 * Class AuthControllerTest.
 *
 * @covers \App\Http\Controllers\Admin\AuthController
 */
class AuthControllerTest extends TestCase
{
    /**
     * @var AuthController
     */
    protected $authController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->authController = new AuthController();
        $this->app->instance(AuthController::class, $this->authController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->authController);
    }
}
