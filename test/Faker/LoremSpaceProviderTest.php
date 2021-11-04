<?php

namespace Mmo\Faker\Test;

use InvalidArgumentException;
use Mmo\Faker\LoremSpaceProvider;
use PHPUnit\Framework\TestCase;

class LoremSpaceProviderTest extends TestCase
{
    public function testLoremSpaceUrlUsesDefaultParameters()
    {
        $this->assertSame(
            'https://api.lorem.space/image/album?w=640&h=480',
            LoremSpaceProvider::loremSpaceUrl(LoremSpaceProvider::CATEGORY_ALBUM)
        );
    }

    public function testLoremSpaceUrlAcceptsCustomWidthAndHeight()
    {
        $this->assertSame(
            'https://api.lorem.space/image/book?w=500&h=500',
            LoremSpaceProvider::loremSpaceUrl(LoremSpaceProvider::CATEGORY_BOOK, 500, 500)
        );
    }

    public function testNotSupportedCategory()
    {
        $this->expectException(InvalidArgumentException::class);
        if (method_exists($this, 'expectExceptionMessageMatches')) {
            $this->expectExceptionMessageMatches('/^Invalid image category/');
        } else {
            $this->expectExceptionMessageRegExp('/^Invalid image category/');
        }

        LoremSpaceProvider::loremSpaceUrl('foo');
    }

    public function testNotSupportedCategoryForDownload()
    {
        $this->expectException(InvalidArgumentException::class);
        if (method_exists($this, 'expectExceptionMessageMatches')) {
            $this->expectExceptionMessageMatches('/^Invalid image category/');
        } else {
            $this->expectExceptionMessageRegExp('/^Invalid image category/');
        }

        LoremSpaceProvider::loremSpace('foo');
    }

    /**
     * @dataProvider normalizeImageSizeProvider
     */
    public function testNormalizeImageSizeValue(int $width, int $height, int $expectedSize)
    {
        $this->assertRegExp(
            sprintf('#[\?&]w=%d#', $expectedSize),
            LoremSpaceProvider::loremSpaceUrl(LoremSpaceProvider::CATEGORY_BOOK, $width, $height)
        );

        $this->assertRegExp(
            sprintf('#[\?&]h=%d#', $expectedSize),
            LoremSpaceProvider::loremSpaceUrl(LoremSpaceProvider::CATEGORY_BOOK, $width, $height)
        );
    }

    public function testPicsumDownloadWithDefaults()
    {
        $file = LoremSpaceProvider::loremSpace(LoremSpaceProvider::CATEGORY_FACE, sys_get_temp_dir());
        $this->assertFileExists($file);

        if (function_exists('getimagesize')) {
            list($width, $height, $type) = getimagesize($file);
            $this->assertEquals(640, $width);
            $this->assertEquals(480, $height);
            $this->assertEquals(constant('IMAGETYPE_JPEG'), $type);
        } else {
            $this->assertEquals('jpg', pathinfo($file, PATHINFO_EXTENSION));
        }
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function normalizeImageSizeProvider(): iterable
    {
        yield 'too_small' => [0, 0, 8];
        yield 'too_big' => [3000, 3000, 2000];
    }
}
