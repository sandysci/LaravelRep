<?php

namespace Tests\Feature\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\ImageController;
use Tests\TestCase;

/**
 * Class ImageControllerTest.
 *
 * @covers \App\Http\Controllers\API\v1\ImageController
 */
class ImageControllerTest extends TestCase
{
    /**
     * @var ImageController
     */
    protected $imageController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->imageController = new ImageController();
        $this->app->instance(ImageController::class, $this->imageController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->imageController);
    }
}
