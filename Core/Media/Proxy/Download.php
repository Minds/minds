<?php

/**
 * Minds Media Proxy Download
 *
 * @author emi
 */

namespace Minds\Core\Media\Proxy;

use Minds\Core\Di\Di;
use Minds\Core\Http\Curl\Client;

class Download
{
    /** @var Client $http*/
    protected $http;

    /** @var string $src */
    protected $src;

    /** @var int $timeout */
    protected $timeout = 2;

    public function __construct($http = null)
    {
        $this->http = $http ?: Di::_()->get('Http');
    }

    /**
     * @param string $src
     * @return Download
     */
    public function setSrc($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @param int $timeout
     * @return Download
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Checks if src is a supported URL.
     * @return bool
     */
    public function isValidSrc()
    {
        return filter_var($this->src, FILTER_VALIDATE_URL) && strpos($this->src, 'http') === 0;
    }

    public function download()
    {
        if (!$this->src || !$this->isValidSrc()) {
            throw new \Exception('Invalid URL');
        }

        $content = $this->http->get($this->src, [
            'curl' => [
                CURLOPT_USERAGENT => 'MindsMediaProxy/3.0 (+http://www.minds.com/)',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_NOSIGNAL => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT_MS => $this->timeout * 1000,
                CURLOPT_TIMEOUT_MS => $this->timeout * 1000,
            ]
        ]);

        if (!$content) {
            throw new \Exception('Invalid image');
        }

        $finfo = new \finfo(FILEINFO_MIME);
        $mime = $finfo->buffer($content);

        if (!$mime) {
            throw new \Exception('Cannot read image MIME');
        } elseif (strpos($mime, 'image/') !== 0) {
            throw new \Exception('Content is not an image');
        }

        $resource = imagecreatefromstring($content);

        if (!$resource) {
            throw new \Exception('Image type not supported');
        }

        return $resource;
    }
}
