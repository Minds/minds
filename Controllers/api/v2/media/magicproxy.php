<?php

/**
 * Minds Media Proxy using Imagick.
 *
 * @author Brian Hatchet
 */

namespace Minds\Controllers\api\v2\media;

use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Core\Media\Proxy\MagicResize;

class MagicProxy implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    const MAX_TIME = 5;

    private $downloader;
    private $resizer;
    private $config;

    public function __construct($downloader = null, MagicResize $resizer = null, $config = null)
    {
        $this->downloader = $downloader ?: Di::_()->get('Media\Proxy\Download');
        $this->resizer = $resizer ?: Di::_()->get('Media\Proxy\MagicResize');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Equivalent to HTTP GET method.
     *
     * @param array $pages
     *
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
            $siteUrl = $this->config->get('site_url');
            $cdnUrl = $this->config->get('cdn_url');
            //If not on minds or the cdn
            if (($siteUrl && strpos($src, $siteUrl) !== 0)
                 && ($cdnUrl && strpos($src, $cdnUrl) !== 0)) {
                header('X-Minds-Exception: not a Minds resource');
                http_response_code(415);
                exit;
            }

            if (strpos($src, '//') === 0) {
                $src = 'https:'.$src;
            }
        } else {
            header('X-Minds-Exception: missing image src');
            http_response_code(415);
            exit;
        }

        $roundX = isset($_GET['roundX']) ? (int) $_GET['roundX'] : null;
        $roundY = isset($_GET['roundY']) ? (int) $_GET['roundY'] : null;

        try {
            set_time_limit(static::MAX_TIME + 1);
            ini_set('max_execution_time', static::MAX_TIME + 1);

            $this->downloader->setLimitKb(2048);

            /** @var Resize $resizer */
            $magicResizer = Di::_()->get('Media\Proxy\MagicResize');
            error_log($src);
            $binaryString = $this->downloader
                ->setSrc($src)
                ->setTimeout(static::MAX_TIME)
                ->downloadBinaryString();

            $this->resizer
                ->setSize($size)
                ->setUpscale(false)
                ->setImage($binaryString)
                ->resize();

            if ($roundX && $roundY) {
                $this->resizer->setImageFormat('png');
                $this->resizer->roundCorners($roundX, $roundY);
            }

            $output = $this->resizer->getImage();

            $expires = date('r', strtotime('+6 months'));

            header("Expires: {$expires}", true);
            header('Pragma: public');
            header('Cache-Control: public');
            header("Content-Type: image/{$this->resizer->getImageFormat()}");

            echo $output;
        } catch (\Exception $e) {
            error_log($e);
            header("X-Minds-Exception: {$e->getMessage()}");
            http_response_code(415);
        }

        exit;
    }

    /**
     * Equivalent to HTTP POST method.
     *
     * @param array $pages
     *
     * @return mixed|null
     */
    public function post($pages)
    {
        http_response_code(501);
        exit;
    }

    /**
     * Equivalent to HTTP PUT method.
     *
     * @param array $pages
     *
     * @return mixed|null
     */
    public function put($pages)
    {
        http_response_code(501);
        exit;
    }

    /**
     * Equivalent to HTTP DELETE method.
     *
     * @param array $pages
     *
     * @return mixed|null
     */
    public function delete($pages)
    {
        http_response_code(501);
        exit;
    }
}
