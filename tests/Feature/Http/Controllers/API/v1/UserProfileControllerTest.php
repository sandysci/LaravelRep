<?php

namespace Tests\Feature\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\UserProfileController;
use Tests\TestCase;

/**
 * Class UserProfileControllerTest.
 *
 * @covers \App\Http\Controllers\API\v1\UserProfileController
 */
class UserProfileControllerTest extends TestCase
{
    /**
     * @var UserProfileController
     */
    protected $userProfileController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->userProfileController = new UserProfileController();
        $this->app->instance(UserProfileController::class, $this->userProfileController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->userProfileController);
    }
}
