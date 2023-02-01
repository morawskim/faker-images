<?php

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;

class LoremFacesProvider extends BaseProvider
{
    public const MAX_IMAGE_ID = 6796;
    public const MIN_IMAGE_ID = 1;

    public static function loremFacesUrl($imageId = null)
    {
        return self::buildLoremFacesUrl($imageId);
    }

    public static function loremFaces($size = 120, $imageId = null, $dir = null, $fullPath = true)
    {
        $url = self::buildLoremFacesUrl($imageId);

        $path =  DownloaderHelper::fetchImage($url, $dir, true);
        ThumbnailHelper::createThumbnail($path, $size);

        return $fullPath ? $path : basename($fullPath);
    }

    private static function buildLoremFacesUrl(?int $imageId): string
    {
        if (null === $imageId) {
            $imageId = random_int(self::MIN_IMAGE_ID, self::MAX_IMAGE_ID);
        }

        if ($imageId > self::MAX_IMAGE_ID || $imageId < self::MIN_IMAGE_ID) {
            throw new \BadMethodCallException(sprintf(
                'ImageId need to be in range %d - %d. Got %d',
                self::MIN_IMAGE_ID,
                self::MAX_IMAGE_ID,
                $imageId
            ));
        }

        return sprintf('https://faces-img.xcdn.link/image-lorem-face-%d.jpg', $imageId);
    }
}
