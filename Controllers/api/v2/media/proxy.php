<?php

/**
 * Minds Media Proxy
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\media;

use Minds\Core\Di\Di;
use Minds\Core\Media\Proxy\Download;
use Minds\Core\Media\Proxy\Resize;
use Minds\Interfaces;

class proxy implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    const MAX_TIME = 5;

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $src = isset($_GET['src']) ? $_GET['src'] : null;

        // thumbProxy polyfill
        $width = isset($_GET['size']) ? (int) $_GET['size'] : null;

        if ($width && is_numeric($width)) {
            $size = $width;
        } else {
            $size = isset($_GET['size']) ? (int) $_GET['size'] : 1024;
        }

        if ($src) {
            $siteUrl = Di::_()->get('Config')->get('site_url');
            $cdnUrl = Di::_()->get('Config')->get('cdn_url');

            if ($siteUrl && strpos($src, $siteUrl) === 0) {
                \forward($src);
                exit;
            } elseif ($cdnUrl && strpos($src, $cdnUrl) === 0) {
                \forward($src);
                exit;
            } elseif (strpos($src, '//') === 0) {
                $src = 'https:' . $src;
            }
        }

        if ($size < 0) {
            exit;
        }

        try {
            set_time_limit(static::MAX_TIME + 1);
            ini_set('max_execution_time', static::MAX_TIME + 1);

            /** @var Download $downloader */
            $downloader = Di::_()->get('Media\Proxy\Download');

            /** @var Resize $resizer */
            $resizer = Di::_()->get('Media\Proxy\Resize');

            $original = $downloader
                ->setSrc($src)
                ->setTimeout(static::MAX_TIME)
                ->download();

            $output = $resizer
                ->setImage($original)
                ->setSize($size)
                ->setUpscale(false)
                ->resize()
                ->getJpeg(75);

            $expires = date('r', strtotime('+6 months'));

            header("Expires: {$expires}", true);
            header('Pragma: public');
            header('Cache-Control: public');
            header('Content-Type: image/jpeg');

            echo $output;
        } catch (\Exception $e) {
            header("X-Minds-Exception: {$e->getMessage()}");
            http_response_code(415);
        }

        exit;
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        http_response_code(501);
        exit;
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        http_response_code(501);
        exit;
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        http_response_code(501);
        exit;
    }
}
