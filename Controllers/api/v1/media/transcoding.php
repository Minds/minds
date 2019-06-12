<?php
/**
 * Minds Media Albums API.
 *
 * @version 1
 *
 * @author Emi Balbuena
 */

namespace Minds\Controllers\api\v1\media;

use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Media\Services\Factory as ServiceFactory;

class transcoding implements Interfaces\Api
{
    /**
     * Return the transcoding status.
     *
     * API:: /v1/media/transcoding/guid
     */
    public function get($pages)
    {
        $videoGUID = $pages[0] ?? null;
        if (!$videoGUID) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the guid of a video',
            ]);
        }

        /** @var EntitiesBuilder $entitiesBuilder */
        $entitiesBuilder = Di::_()->get('EntitiesBuilder');
        $entity = $entitiesBuilder->single($videoGUID);
        if (!$entity) {
            return Factory::response([
                'status' => 'error',
                'error' => 'Cannot find that video. Please, upload again.',
            ]);
        }
        if (!$entity instanceof Entities\Video) {
            return Factory::response([
                'status' => 'error',
                'error' => 'Not a video resource.',
            ]);
        }
        
        $transcoder = ServiceFactory::build('FFMpeg');
        $transcodingStatus = $transcoder->verify($entity);
        
        if (!$transcodingStatus->hasSource()) {
            return Factory::response([
                'transcoding' => false,
                'error' => 'This video has been deleted. Please, upload again.',
            ]);
        }
        if ($transcodingStatus->isTranscodingComplete()) {
            return Factory::response([
                'transcoding' => false,
                'message' => 'transcoding complete',
                'transcode'=> $transcodingStatus->getTranscodes()
            ]);
        }

        return Factory::response([
            'transcoding' => true,
            'message' => 'This video is transcoding',
            'transcodes'=> $transcodingStatus->getTranscodes()
        ]);
    }

    /**
     * POST method.
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT Method.
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE Method.
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
