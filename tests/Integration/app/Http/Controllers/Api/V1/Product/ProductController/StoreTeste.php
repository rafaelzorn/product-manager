<?php

namespace Tests\Integration\app\Http\Controllers\Api\V1\Product\ProductController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/api/v1/products/';

    /**
     * @test
     *
     * @return void
     */
    public function should_store_product(): void
    {
        $this->assertTrue(true);
    }
}
