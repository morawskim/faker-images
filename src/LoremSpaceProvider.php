<?php

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;
use InvalidArgumentException;

class LoremSpaceProvider extends BaseProvider
{
    public const CATEGORY_MOVIE = 'movie';
    public const CATEGORY_GAME = 'game';
    public const CATEGORY_ALBUM = 'album';
    public const CATEGORY_BOOK = 'book';
    public const CATEGORY_FACE = 'face';
    public const CATEGORY_FASHION = 'fashion';
    public const CATEGORY_SHOES = 'shoes';
    public const CATEGORY_WATCH = 'watch';
    public const CATEGORY_FURNITURE = 'furniture';
    public const CATEGORY_CAR = 'car';

    private static $CATEGORIES = [
        self::CATEGORY_MOVIE,
        self::CATEGORY_GAME,
        self::CATEGORY_ALBUM,
        self::CATEGORY_BOOK,
        self::CATEGORY_FACE,
        self::CATEGORY_FASHION,
        self::CATEGORY_SHOES,
        self::CATEGORY_WATCH,
        self::CATEGORY_FURNITURE,
        self::CATEGORY_CAR,
    ];

    public static function loremSpaceUrl($category, $width = 640, $height = 480)
    {
        return self::buildLoremSpaceUrl($category, self::buildQueryString($width, $height));
    }

    public static function loremSpace($category, $dir = null, $width = 640, $height = 480, $fullPath = true)
    {
        $url = self::buildLoremSpaceUrl($category, self::buildQueryString($width, $height));

        return DownloaderHelper::fetchImage($url, $dir, $fullPath);
    }

    private static function buildQueryString(int $width, int $height)
    {
        $queryParams = array();
        $queryParams['w'] = max(min($width, 2000), 8);
        $queryParams['h'] = max(min($height, 2000), 8);

        return '?' . http_build_query($queryParams);
    }

    private static function buildLoremSpaceUrl($category, $queryString)
    {
        if (!in_array($category, self::$CATEGORIES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid image category "%s"', $category));
        }

        $baseUrl = 'https://api.lorem.space/image/';

        return $baseUrl . $category . $queryString;
    }
}
