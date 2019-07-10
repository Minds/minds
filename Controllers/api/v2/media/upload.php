<?php

/**
 * Client based upload 
 *
 * @author Mark Harding
 */

namespace Minds\Controllers\api\v2\media;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Core\Media\ClientUpload\ClientUploadLease;

class upload implements Interfaces\Api
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
        $manager = Di::_()->get("Media\ClientUpload\Manager");
        switch ($pages[0]) {
            case 'prepare':
                $mediaType = $pages[1] ?? 'not-set';
                $lease = $manager->prepare($mediaType);
                return Factory::response([
                    'lease' => $lease->export(),
                ]);
            break;
            case 'complete':
                $mediaType = $pages[1] ?? 'not-set';
                $guid = $pages[2] ?? null;

                $lease = new ClientUploadLease();
                $lease->setGuid($guid)
                    ->setMediaType($mediaType);

                $manager->complete($lease);
            break;
        }
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

