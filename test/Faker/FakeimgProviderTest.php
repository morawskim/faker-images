<?php

namespace Faker;

use Mmo\Faker\FakeimgProvider;
use PHPUnit\Framework\TestCase;

class FakeimgProviderTest extends TestCase
{
    public function testFakeImgDefaultParameters()
    {
        $this->assertSame(
            'https://fakeimg.pl/640x480/CCCCCC/939393',
            FakeimgProvider::fakeImgUrl()
        );
    }

    public function testFakeImgCustomWidthAndHeight()
    {
        $this->assertSame(
            'https://fakeimg.pl/800x600/CCCCCC/939393',
            FakeimgProvider::fakeImgUrl(800, 600)
        );
    }

    public function testFakeImgCustomText()
    {
        $this->assertSame(
            'https://fakeimg.pl/800x600/CCCCCC/939393?text=foo+%26+bar',
            FakeimgProvider::fakeImgUrl(800, 600, 'foo & bar')
        );
    }

    public function testFakeImgAllParameteres()
    {
        $this->assertSame(
            'https://fakeimg.pl/800x600/649664/000000?retina=1&text=foo+%26+bar',
            FakeimgProvider::fakeImgUrl(
                800,
                600,
                'foo & bar',
                [100, 150, 100],
                [0, 0, 0],
                true
            )
        );
    }

    public function testFakeImgColorsWithAlpha()
    {
        $this->assertSame(
            'https://fakeimg.pl/800x600/649664,128/000000,10?retina=1&text=foo+%26+bar',
            FakeimgProvider::fakeImgUrl(
                800,
                600,
                'foo & bar',
                [100, 150, 100, 128],
                [0, 0, 0, 10],
                true
            )
        );
    }

    public function testFakeImgColors()
    {
        $this->assertSame(
            'https://fakeimg.pl/800x600/FF0000,128/000000,10?retina=1&text=foo+%26+bar',
            FakeimgProvider::fakeImgUrl(
                800,
                600,
                'foo & bar',
                [255, 0, 0, 128],
                [0, 0, 0, 10],
                true
            )
        );
    }

    /**
     * @dataProvider invalidColorProvider
     */
    public function testFakeImgInvalidColor(array $invalidColor, string $exceptionMessage)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        FakeimgProvider::fakeImgUrl(
            800,
            600,
            '',
            $invalidColor
        );
    }

    public static function invalidColorProvider()
    {
        yield 'too_many_elements' => [[1, 2, 3, 4, 5], 'more than 4 elements'];
        yield 'not_enough_elements' => [[1, 2], ' at least 3 elements'];
        yield 'value_out_of_range_above' => [[1, 2, 300], 'in the range'];
        yield 'value_out_of_range_below' => [[1, 2, -100], 'in the range'];
    }

    public function testFakeImgDownloadWithDefaults()
    {
        $file = FakeimgProvider::fakeImg(sys_get_temp_dir());
        $this->assertFileExists($file);

        if (function_exists('getimagesize')) {
            list($width, $height, $type) = getimagesize($file);
            $this->assertEquals(640, $width);
            $this->assertEquals(480, $height);
            $this->assertEquals(constant('IMAGETYPE_PNG'), $type);
        } else {
            $this->assertEquals('png', pathinfo($file, PATHINFO_EXTENSION));
        }
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
