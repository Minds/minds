<?php
/**
 * Minds Comments API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\comments;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;
use Minds\Core\Sockets;

class disable implements Interfaces\Api
{
    public function get($pages) {
        return Factory::response([]);
    }

    public function post($pages) {
        return Factory::response([]);
    }

    public function put($pages) {
        $response = [];

        if (is_numeric($pages[0])) {
            $activity = Core\Entities::build(new Entities\Entity($pages[0]));
            //$activity = new Entities\Activity($pages[0]);
            $activity->enableComments();

            $success = $activity->save(); // should check if it was successful?

            if (!$success) {
                $response = ['status' => 'error', 'message' => 'Error while enabling comments'];
            } else {
                $response = ['entity' => $activity->export()];
            }

        }

        return Factory::response($response);
    }

    public function delete($pages)  {
        $response = [];

        if (is_numeric($pages[0])) {
            $activity = Core\Entities::build(new Entities\Entity($pages[0]));
            $activity->disableComments();

            $success = $activity->save(); // should check if it was successful?

            if (!$success) {
                $response = ['status' => 'error', 'message' => 'Error while disabling comments'];
            } else {
                $response = ['entity' => $activity->export()];
            }
        }

        return Factory::response($response);
    }

}
