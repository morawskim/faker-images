<?PHP

namespace Mmo\Faker;

use Faker\Provider\Base as BaseProvider;

class PicsumProvider extends BaseProvider
{
    public static function picsumUrl($width = 640, $height = 480, $id = null, $randomize = true, $gray = false, $blur = null)
    {
        $baseUrl = 'https://picsum.photos/';
        $url = '';
        if ($id) {
            $url = 'id/' . $id . '/';
        }
        $url .= "{$width}/{$height}";
        $queryString = self::buildQueryString($gray, $blur, $randomize);
        return $baseUrl . $url . $queryString;
    }

    public static function picsumStaticRandomUrl($width = 640, $height = 480, $gray = false, $blur = null)
    {
        $baseUrl = 'https://picsum.photos/';

        $url = 'seed/' . uniqid() . '/' . "{$width}/{$height}";
        $queryString = self::buildQueryString($gray, $blur, null);

        return $baseUrl . $url . $queryString;
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public static function picsum($dir = null, $width = 640, $height = 480, $fullPath = true, $id = null, $randomize = true, $gray = false, $blur = null)
    {
        $url = static::picsumUrl($width, $height, $id, $randomize, $gray, $blur);
        return self::fetchImage($url, $dir, $fullPath);
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @param string $url Image url to fetch
     * @param null|string $dir Directory where downloaded image will be stored
     * @param bool $fullPath Return full path to file or only filename
     * @return bool|\RuntimeException|string
     */
    private static function fetchImage($url, $dir = null, $fullPath = true)
    {
        $dir = $dir === null ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible
        // Validate directory path
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name .'.jpg';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        // save file
        if (function_exists('curl_exec')) {
            // use cURL
            $fp = fopen($filepath, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
            fclose($fp);
            curl_close($ch);

            if (!$success) {
                unlink($filepath);

                // could not contact the distant URL or HTTP error - fail silently.
                return false;
            }
        } elseif (ini_get('allow_url_fopen')) {
            // use remote fopen() via copy()
            $success = copy($url, $filepath);
        } else {
            return new \RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        return $fullPath ? $filepath : $filename;
    }

    /**
     * @param boolean|null $gray
     * @param int|null $blur
     * @param boolean|null $randomize
     * @return string
     */
    private static function buildQueryString($gray, $blur, $randomize)
    {
        $queryParams = array();
        if ($gray) {
            $queryParams['grayscale'] = '';
        }
        if ($blur) {
            $queryParams['blur'] = '';
        }
        if ($randomize) {
            $queryParams['random'] = static::randomNumber(5, true);
        }
        $queryString = '';
        if (!empty($queryParams)) {
            $queryString = '?' . http_build_query($queryParams);
        }
        return $queryString;
    }
}
