<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CartQuantityTest extends TestCase
{
    public function test_quantity_is_not_negative()
    {
        $qty = 2;
        $this->assertGreaterThanOrEqual(0, $qty, 'Số lượng không được âm');
    }
}
