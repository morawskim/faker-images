<?php

namespace Mmo\Faker\Test;

use Mmo\Faker\FakeimgUtils;
use PHPUnit\Framework\TestCase;

class FakeimgUtilsTest extends TestCase
{
    public function testCreateColor()
    {
        $color = FakeimgUtils::createColor(255, 0, 0);
        $this->assertEquals([255, 0, 0, 255], $color);
    }

    public function testCreateColorWithAlpha()
    {
        $color = FakeimgUtils::createColor(0, 255, 0, 128);
        $this->assertEquals([0, 255, 0, 128], $color);
    }
}
