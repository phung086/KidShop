<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase
{
    public function test_discount_calculation()
    {
        $price = 200000;
        $discount = 20;
        $expected = $price * (1 - $discount / 100);

        $this->assertEquals(160000, $expected);
    }
}
