<?php

namespace Mmo\Faker;

class ThumbnailHelper
{
    public static function createThumbnail($path, $dimension, $outputPath = null): void
    {
        $mime = getimagesize($path);
        $outputPath = $outputPath ?? $path;

        if ('image/png' === $mime['mime']) {
            $imageResource = imagecreatefrompng($path);
        } elseif (in_array($mime['mime'], ['image/jpg', 'image/jpeg'], true)) {
            $imageResource = imagecreatefromjpeg($path);
        } else {
            throw new \RuntimeException('Only JPG/JPEG/PNG images are supported');
        }

        $originalX = imagesx($imageResource);
        $originalY = imagesy($imageResource);

        $thumbnailResource = imagecreatetruecolor($dimension, $dimension);
        imagecopyresampled(
            $thumbnailResource,
            $imageResource,
            0,
            0,
            0,
            0,
            $dimension,
            $dimension,
            $originalX,
            $originalY
        );

        if ('image/png' === $mime['mime']) {
            $result = imagepng($thumbnailResource, $outputPath, 8);
            self::throwExceptionIfError($result);

            imagedestroy($thumbnailResource);
            imagedestroy($imageResource);
        } elseif (in_array($mime['mime'], ['image/jpg', 'image/jpeg'], true)) {
            $result = imagejpeg($thumbnailResource, $outputPath, 80);
            self::throwExceptionIfError($result);

            imagedestroy($thumbnailResource);
            imagedestroy($imageResource);
        }
    }

    private static function throwExceptionIfError($result): void
    {
        if (!$result) {
            throw new \RuntimeException('Cannot save thumbnail');
        }
    }
}
