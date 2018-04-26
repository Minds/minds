<?php

/**
 * Minds Media Magnet Generator
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\media;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Torrent\TorrentMeta;
use Minds\Entities\Video;
use Minds\Interfaces;

class magnet implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $config = Di::_()->get('Config');

        if (!isset($pages[0]) || !is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid GUID'
            ]);
        }

        if (!isset($pages[1])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid file name'
            ]);
        }

        $entity = new Video($pages[0]);

        $corsQuery = str_replace('://', '_', trim(Di::_()->get('Config')->get('site_url'), ''));
        $src = $entity->getSourceUrl($pages[1]) . '?' . $corsQuery;

        try {
            $torrentMeta = new TorrentMeta();
            $torrentMeta
                ->setEntity($entity)
                ->setFile($pages[1])
                ->setSource($src)
                ->setXs($config->get('site_url') . 'api/v2/media/torrent/' . $pages[0] . '/' . $pages[1] . '.torrent');

            return Factory::response([
                'httpSrc' => $src,
                'infoHash' => $torrentMeta->infoHash(),
                'magnet' => $torrentMeta->magnet(),
                'encodedTorrent' => $torrentMeta->encodedTorrent()
            ]);
        } catch (\Exception $e) {
            error_log("[magnet::get] {$e->getMessage()} : " . get_class($e));

            return Factory::response([
                'status' => 'error',
                'message' => 'Error generating torrent file'
            ]);
        }
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
