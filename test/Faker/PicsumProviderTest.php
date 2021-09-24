<?php

namespace Mmo\Faker\Test;

use Mmo\Faker\PicsumProvider;
use PHPUnit\Framework\TestCase;

class PicsumProviderTest extends \PHPUnit\Framework\TestCase
{
    public function testPicsumUrlUses640x680AsTheDefaultSize()
    {
        $this->assertRegExp('#^https://picsum.photos/640/480#', PicsumProvider::picsumUrl());
    }

    public function testPicsumUrlAcceptsCustomWidthAndHeight()
    {
        $this->assertRegExp('#^https://picsum.photos/800/400#', PicsumProvider::picsumUrl(800, 400));
    }

    public function testPicsumUrlWithBlur()
    {
        $this->assertRegExp('#^https://picsum\.photos/800/400\?blur=#', PicsumProvider::picsumUrl(800, 400, null, false, false, true));
    }

    public function testPicsumUrlGray()
    {
        $this->assertRegExp('#^https://picsum\.photos/800/400\?grayscale=#', PicsumProvider::picsumUrl(800, 400, null, false, true));
    }

    public function testPicsumWithIdAndCustomWidthAndHeight()
    {
        $this->assertRegExp('#^https://picsum.photos/id/871/800/400#', PicsumProvider::picsumUrl(800, 400, 871));
    }

    public function testPicsumUrlWithGrayAndBlur()
    {
        $imageUrl = PicsumProvider::picsumUrl(
            800,
            400,
            null,
            false,
            true,
            true
        );

        $this->assertSame('https://picsum.photos/800/400?grayscale=&blur=', $imageUrl);
    }

    public function testpicsumStaticRandomUrl()
    {
        $imageUrl = PicsumProvider::picsumStaticRandomUrl(
            800,
            400
        );

        $this->assertRegExp('#^https://picsum\.photos/seed/[A-Za-z0-9]+/800/400#', $imageUrl);
    }

    public function testPicsumStaticRandomUrlWithCustomParameters()
    {
        $imageUrl = PicsumProvider::picsumStaticRandomUrl(
            800,
            400,
            true,
            true
        );

        $this->assertRegExp('#^https://picsum\.photos/seed/[A-Za-z0-9]+/800/400\?grayscale=&blur=#', $imageUrl);
    }

    public function testpicsumStaticRandomUrlWithGrayAndBlur()
    {
        $imageUrl = PicsumProvider::picsumStaticRandomUrl(
            800,
            400,
            true,
            true
        );

        $this->assertRegExp('#^https:\/\/picsum\.photos\/seed\/[A-Za-z0-9]+\/800\/400+\?grayscale=&blur=#', $imageUrl);
    }

    public function testPicsumUrlAddsARandomGetParameterByDefault()
    {
        $url = PicsumProvider::picsumUrl(800, 400);
        $splitUrl = explode('?', $url);

        $this->assertEquals(count($splitUrl), 2);
        $this->assertRegexp('#random=\d{5}#', $splitUrl[1]);
    }

    public function testPicsumDownloadWithDefaults()
    {
        $file = PicsumProvider::picsum(sys_get_temp_dir());
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

    /**
     * @dataProvider picusmImageExtensionsProvider
     *
     * @param string $imageExtension
     */
    public function testPicsumUrlWithImageExtension($imageExtension)
    {
        $imageUrl = PicsumProvider::picsumUrl(
            800,
            400,
            null,
            false,
            true,
            true,
            $imageExtension
        );
        $expectedString = sprintf('https://picsum.photos/800/400.%s?grayscale=&blur=', $imageExtension);

        $this->assertSame($expectedString, $imageUrl);
    }

    public function testNotSupportedImageExtension()
    {
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid image extension');

        PicsumProvider::picsumUrl(
            800,
            400,
            null,
            false,
            true,
            true,
            'wrongExtension'
        );
    }

    public function picusmImageExtensionsProvider()
    {
        return array(
            array(PicsumProvider::JPG_IMAGE),
            array(PicsumProvider::WEBP_IMAGE),
        );
    }
}
