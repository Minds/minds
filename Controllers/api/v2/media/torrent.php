<?php

/**
 * Minds Media Torrent Generator
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\media;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Torrent\TorrentMeta;
use Minds\Entities\Video;
use Minds\Interfaces;

class torrent implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0]) || !is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid GUID'
            ]);
        }

        if (!isset($pages[1]) || strpos($pages[1], '.torrent') === false) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid file name'
            ]);
        }

        $entity = new Video($pages[0]);
        $filename = str_replace('.torrent', '', $pages[1]);

        $corsQuery = str_replace('://', '_', trim(Di::_()->get('Config')->get('site_url'), ''));
        $src = $entity->getSourceUrl($filename) . '?' . $corsQuery;

        try {
            $torrentMeta = new TorrentMeta();
            $torrentMeta
                ->setEntity($entity)
                ->setFile($filename)
                ->setSource($src);

            echo $torrentMeta->torrent();
        } catch (\Exception $e) {
            error_log("[torrent::get] {$e->getMessage()} : " . get_class($e));
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
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
