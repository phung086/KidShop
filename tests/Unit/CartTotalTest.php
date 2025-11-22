<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CartTotalTest extends TestCase
{
    public function test_cart_total_price_is_correct()
    {
        $item1 = 100000 * 2;
        $item2 = 150000 * 1;
        $total = $item1 + $item2;

        $this->assertEquals(350000, $total);
    }
}
