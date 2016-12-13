<?php
/**
 * Minds Payments API - Subscribers
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class subscribers implements Interfaces\Api
{
    /**
     * Returns an entities subscribers
     * @param array $pages
     *
     * API:: /v1/payments/subscribers/:plan
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
        $repo = new Payments\Plans\Repository();

        $guids = $repo
            ->setEntityGuid(Core\Session::getLoggedInUser()->guid)
            ->getAllSubscribers($pages[1], [
                'offset' => $offset
            ]);

        if ($offset) {
            array_shift($guids);
        }

        $response = [];

        if ($guids) {
            $subscribers = Core\Entities::get([ 'guids' => $guids ]);

            $response['subscribers'] = Factory::exportable($subscribers);

            if ($subscribers) {
                $response['load-next'] = (string) end($subscribers)->guid;
            }
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
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
