<?php

namespace Mmo\Faker\Test;

use Mmo\Faker\LoremFacesProvider;
use Mmo\Faker\LoremSpaceProvider;
use PHPUnit\Framework\TestCase;

class LoremFacesProviderTest extends TestCase
{
    public function testLoremFacesUrl(): void
    {
        $this->assertSame(
            'https://faces-img.xcdn.link/image-lorem-face-123.jpg',
            LoremFacesProvider::loremFacesUrl(123)
        );
    }

    public function testLoremFacesUrlRandomImageId(): void
    {
        $this->assertRegExp(
            '#https://faces-img.xcdn.link/image-lorem-face-\d+.jpg#',
            LoremFacesProvider::loremFacesUrl()
        );
    }

    /**
     * @dataProvider providerInvalidImageId
     */
    public function testLoremFacesImageIdInTheRange(int $imageId): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('ImageId need to be in range');

        LoremFacesProvider::loremFacesUrl($imageId);
    }

    public function providerInvalidImageId(): iterable
    {
        yield 'too_low' => [0];
        yield 'too_big' => [9999];
    }

    public function testLoremFacesDownload(): void
    {
        $size = 150;
        $file = LoremFacesProvider::loremFaces($size, 456, sys_get_temp_dir());
        $this->assertFileExists($file);

        if (function_exists('getimagesize')) {
            list($width, $height, $type) = getimagesize($file);
            $this->assertEquals($size, $width);
            $this->assertEquals($size, $height);
            $this->assertEquals(constant('IMAGETYPE_JPEG'), $type);
        } else {
            $this->assertEquals('jpg', pathinfo($file, PATHINFO_EXTENSION));
        }
        unlink($file);
    }
}
