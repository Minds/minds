<?php
/**
 * Minds Content Rating API
 *
 * @version 1
 * @author Marcelo
 */

namespace Minds\Controllers\api\v1\admin;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Queue;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Interfaces;
use Minds\Core\Entities\Actions\Save;

class rating implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send a GUID.']);
        }
        if (!isset($pages[1])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must send a valid content rating (1 = safe, 2 = open).'
            ]);
        }
        $rating = intval($pages[1]);
        if ($rating !== 1 && $rating !== 2) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Content rating value can only be 1 (safe) or 2 (open).'
            ]);
        }
        $entity = EntitiesFactory::build($pages[0]);

        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'Entity not found.']);
        }

        $entity->setRating($rating);
        
        $save = new Save();
        $save->setEntity($entity)
            ->save();

        Queue\Client::Build()->setQueue("Trending")
            ->send(['a']);

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