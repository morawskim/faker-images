<?php

namespace Mmo\Faker;

class FakeimgUtils
{
    public static function createColor(int $red, int $green, int $blue, int $alpha = 255): array
    {
        return [$red, $green, $blue, $alpha];
    }
}
