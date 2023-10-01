<?php

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;

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

    private static $API_URL = 'https://api.lorem.space/image/';

    public static function setApiUrl(string $url)
    {
        self::$API_URL = rtrim($url, '/') . '/';
    }

    public static function getApiUrl()
    {
        return self::$API_URL;
    }

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

    protected static function buildLoremSpaceUrl($category, $queryString)
    {
        $baseUrl = self::getApiUrl();

        return $baseUrl . $category . $queryString;
    }
}
