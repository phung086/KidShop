<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductPriceTest extends TestCase
{
    /** @test */
    public function product_price_must_be_positive()
    {
        $product = new Product();
        $product->price = 150000;

        $this->assertGreaterThan(0, $product->price);
    }
}
