<?PHP

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;
use InvalidArgumentException;

class PicsumProvider extends BaseProvider
{
    const JPG_IMAGE = 'jpg';
    const WEBP_IMAGE = 'webp';

    private static $IMAGE_EXTENSIONS = array(self::JPG_IMAGE, self::WEBP_IMAGE);

    public static function picsumUrl($width = 640, $height = 480, $id = null, $randomize = true, $gray = false, $blur = null, $imageExtension = null)
    {
        $url = '';
        if ($id) {
            $url = 'id/' . $id . '/';
        }
        $url .= "{$width}/{$height}";
        $queryString = self::buildQueryString($gray, $blur, $randomize);

        return self::buildPicsumUrl($url, $queryString, $imageExtension);
    }

    public static function picsumStaticRandomUrl($width = 640, $height = 480, $gray = false, $blur = null, $imageExtension = null)
    {
        $url = 'seed/' . uniqid() . '/' . "{$width}/{$height}";
        $queryString = self::buildQueryString($gray, $blur, null);

        return self::buildPicsumUrl($url, $queryString, $imageExtension);
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public static function picsum($dir = null, $width = 640, $height = 480, $fullPath = true, $id = null, $randomize = true, $gray = false, $blur = null, $imageExtension = null)
    {
        $url = static::picsumUrl($width, $height, $id, $randomize, $gray, $blur, $imageExtension);

        return DownloaderHelper::fetchImage($url, $dir, $fullPath);
    }

    /**
     * @param boolean|null $gray
     * @param int|null $blur
     * @param boolean|null $randomize
     *
     * @return string
     */
    private static function buildQueryString($gray, $blur, $randomize)
    {
        $queryParams = array();
        $queryString = '';

        if ($gray) {
            $queryParams['grayscale'] = '';
        }

        if ($blur) {
            $queryParams['blur'] = '';
        }

        if ($randomize) {
            $queryParams['random'] = static::randomNumber(5, true);
        }

        if (!empty($queryParams)) {
            $queryString = '?' . http_build_query($queryParams);
        }

        return $queryString;
    }

    private static function buildPicsumUrl($path, $queryString, $imageExtension = null)
    {
        $baseUrl = 'https://picsum.photos/';

        if ($imageExtension) {
            if (!in_array($imageExtension, self::$IMAGE_EXTENSIONS, true)) {
                throw new InvalidArgumentException(sprintf('Invalid image extension "%s"', $imageExtension));
            }
            $path .= '.' . $imageExtension;
        }

        return $baseUrl . $path . $queryString;
    }
}
