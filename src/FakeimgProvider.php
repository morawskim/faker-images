<?php

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;

class FakeimgProvider extends BaseProvider
{
    public static function fakeImgUrl(
        $width = 640,
        $height = 480,
        $text = '',
        array $backgroundColor = [],
        array $fontColor = [],
        $retina = false
    ) {
        return self::buildFakeImgUrl(
            $width,
            $height,
            $backgroundColor,
            $fontColor,
            self::buildQueryString($text, $retina)
        );
    }

    public static function fakeImg(
        $dir = null,
        $width = 640,
        $height = 480,
        $fullPath = true,
        $text = '',
        array $backgroundColor = [],
        array $fontColor = [],
        $retina = false
    ) {
        $url = self::buildFakeImgUrl(
            $width,
            $height,
            $backgroundColor,
            $fontColor,
            self::buildQueryString($text, $retina)
        );

        return DownloaderHelper::fetchImage($url, $dir, $fullPath, 'png');
    }

    private static function buildQueryString($text, $retina)
    {
        $queryParams = array();
        $queryParams['retina'] = $retina;
        $queryParams['text'] = $text;
        $queryParams = array_filter($queryParams);

        if (count($queryParams)) {
            return '?' . http_build_query($queryParams);
        }

        return '';
    }

    private static function buildFakeImgUrl($width, $height, $backgroundColor, $fontColor, $queryString)
    {
        if (!empty($backgroundColor)) {
            self::validateColorStruct($backgroundColor);
        }

        if (!empty($fontColor)) {
            self::validateColorStruct($fontColor);
        }


        return sprintf(
            'https://fakeimg.pl/%dx%d/%s/%s%s',
            $width,
            $height,
            self::colorToString($backgroundColor, 'CCCCCC'),
            self::colorToString($fontColor, '939393'),
            $queryString
        );
    }

    private static function validateColorStruct(array $color)
    {
        $count = count($color);

        if ($count < 3) {
            throw new \InvalidArgumentException(sprintf(
                'The color array "[%s]" is invalid. Must contains at least 3 elements',
                implode(',', $color)
            ));
        }

        if ($count > 4) {
            throw new \InvalidArgumentException(sprintf(
                'The color array "[%s]" is invalid. Cannot contains more than 4 elements',
                implode(',', $color)
            ));
        }

        foreach ($color as $value) {
            if ($value < 0 || $value > 255) {
                throw new \InvalidArgumentException(sprintf(
                    'The color value "%s" of "[%s]" need to be in the range 0 - 255',
                    $value,
                    implode(',', $color)
                ));
            }
        }
    }

    private static function colorToString(array $color, string $defaultColor)
    {
        if (4 === count($color)) {
            return sprintf(
                '%02X%02X%02X,%s',
                $color[0],
                $color[1],
                $color[2],
                $color[3]
            );
        }

        if (3 === count($color)) {
            return sprintf(
                '%02X%02X%02X',
                $color[0],
                $color[1],
                $color[2]
            );
        }

        return $defaultColor;
    }
}
