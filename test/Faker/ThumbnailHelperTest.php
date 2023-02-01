<?php

namespace Faker;

use Mmo\Faker\ThumbnailHelper;
use PHPUnit\Framework\TestCase;

class ThumbnailHelperTest extends TestCase
{
    /**
     * @dataProvider providerImagesPaths
     */
    public function testCreateThumbnail(string $image): void
    {
        if (!function_exists('getimagesize')) {
            $this->markTestSkipped('The PHP function getimagesize is not available');
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'thumbnail-helper-test');
        ThumbnailHelper::createThumbnail($image, 200, $tmpFile);

        list($width, $height) = getimagesize($tmpFile);
        $this->assertEquals(200, $width);
        $this->assertEquals(200, $height);
    }

    public function providerImagesPaths(): iterable
    {
        yield 'png' => [__DIR__ . '/../resources/128-400x400.png'];
        yield 'jpeg' => [__DIR__ . '/../resources/128-400x400.jpg'];
    }
}
