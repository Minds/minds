<?php
namespace Minds\Controllers\api\v2\admin\media;

use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Media\Services\Factory as ServiceFactory;
use Minds\Api\Factory;
use Minds\Core\Media\Services\FFMpeg;
use Minds\Interfaces;

class transcode implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        /** @var EntitiesBuilder $entitiesBuilder */
        $entitiesBuilder = Di::_()->get('EntitiesBuilder');

        if (!$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'GUID is required'
            ]);
        }

        $entity = $entitiesBuilder->single($pages[0]);

        if (!$entity || $entity->subtype !== 'video') {
            return Factory::response([
                'status' => 'error',
                'message' => 'Entity is not a video'
            ]);
        }

        /** @var FFMpeg $transcoder */
        $transcoder = ServiceFactory::build('FFMpeg');

        $transcoder
            ->setKey($entity->guid)
            ->transcode();

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
