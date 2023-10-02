<?php

namespace Mmo\Faker\Test;

use Mmo\Faker\LoremSpaceProvider;
use PHPUnit\Framework\TestCase;

class LoremSpaceProviderTest extends TestCase
{
    /**
     * @after
     */
    protected function resetApiUrl()
    {
        LoremSpaceProvider::setApiUrl('https://api.lorem.space/image/');
    }

    public function testGetterAndSetterApiUrl()
    {
        $apiUrl = LoremSpaceProvider::getApiUrl();
        $this->assertSame('https://api.lorem.space/image/', $apiUrl);

        LoremSpaceProvider::setApiUrl('https://loremspace.example.com/image/');
        $apiUrl = LoremSpaceProvider::getApiUrl();
        $this->assertSame('https://loremspace.example.com/image/', $apiUrl);
        $this->assertSame(
            'https://loremspace.example.com/image/album?w=640&h=480',
            LoremSpaceProvider::loremSpaceUrl(LoremSpaceProvider::CATEGORY_ALBUM)
        );
    }

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

    public function normalizeImageSizeProvider(): iterable
    {
        yield 'too_small' => [0, 0, 8];
        yield 'too_big' => [3000, 3000, 2000];
    }

    public function testSelfOwnedLoremSpaceUrl()
    {
        $selfOwnedLoremSpaceUrl = getenv('LOREM_SPACE_SELF_OWNED_URL');

        if (empty($selfOwnedLoremSpaceUrl)) {
            self::markTestSkipped(sprintf('The env variable "%s" is not set', 'LOREM_SPACE_SELF_OWNED_URL'));
        }

        LoremSpaceProvider::setApiUrl($selfOwnedLoremSpaceUrl);
        $file = LoremSpaceProvider::loremSpace('mycategory', sys_get_temp_dir());
        $this->checkDownloadedFile($file);
    }

    private function checkDownloadedFile($file): void
    {
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
}
