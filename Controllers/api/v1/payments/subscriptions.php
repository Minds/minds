<?php
/**
 * Minds Payments API - Subscriptions
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class subscriptions implements Interfaces\Api
{
    /**
     * Returns user subscriptions
     * @param array $pages
     *
     * API:: /v1/payments/subscriptions/:plan
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
        $repo = new Payments\Plans\Repository();

        $guids = $repo
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->getAllSubscriptions($pages[0], [
                'offset' => $offset 
            ]);

        if ($offset) {
            array_shift($guids);
        }

        $response = [];

        if ($guids) {
            $subscriptions = Core\Entities::get([ 'guids' => $guids ]);
            
            $response['subscriptions'] = Factory::exportable($subscriptions);

            if ($subscriptions) {
                $response['load-next'] = (string) end($subscriptions)->guid;
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
