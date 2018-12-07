<?php


namespace Minds\Controllers\api\v2\video;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\VideoChat\Manager;
use Minds\Interfaces;

/**
 * Class room
 * Generates a room ID for a given entity
 * @package Minds\Controllers\api\v1
 */
class room implements Interfaces\Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must send a guid'
            ]);
        }
        $entity = Di::_()->get('EntitiesBuilder')->single($pages[0]);

        if (!$entity) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid guid'
            ]);
        }

        /** @var Manager $manager */
        $manager = Di::_()->get('VideoChat\Manager');

        $manager->setEntity($entity);

        return Factory::response([
            'status' => 'success',
            'room' => $manager->getRoomKey()
        ]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must set a room name'
            ]);
        }

        $roomName = $pages[0];

        Di::_()->get('VideoChat\Manager')
            ->refreshTTL($roomName);

        return Factory::response([
            'status' => 'success'
        ]);
        return Factory::response([]);
    }

    public function put($pages)
    {

        return Factory::response([]);
    }

    public function delete($pages)
    {

        return Factory::response([]);
    }

}