<?php
/**
 * Minds Payments API - Subscriptions
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Payments;
use Minds\Interfaces;

class subscriptions implements Interfaces\Api
{
    /**
     * Returns user subscriptions
     * @param array $pages
     *
     * API:: /v1/payments/subscriptions
     */
    public function get($pages)
    {
        if (!isset($_GET['plansIds'])) {
            return Factory::response([]);
        }
        $plansIds = $_GET['plansIds'];
        if (!is_array($plansIds)) {
            $plansIds = explode(',', $plansIds);
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
        $repo = new Payments\Plans\Repository();

        $guids = $repo
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->getAllSubscriptions($plansIds, [
                'offset' => $offset 
            ]);

        if ($offset) {
            array_shift($guids);
        }

        $response = [];

        if ($guids) {
            foreach($guids as $guid) {
                $entity = Core\Entities::get([ 'guids' => [$guid[0]] ])[0]->export();
                $subscriptions[] = [$entity, $guid[1]];
            }

            $response['subscriptions'] = $subscriptions;

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
