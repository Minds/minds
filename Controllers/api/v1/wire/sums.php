<?php
/**
 * Minds Wire Sums
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\wire;

use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Wire;
use Minds\Entities;
use Minds\Entities\User;

class sums implements Interfaces\Api
{
    /**
     * GET
     */
    public function get($pages)
    {
        $response = [];
        $repo = Di::_()->get('Wire\Repository');

        switch ($pages[0]) {
            case "receiver":
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $method = isset($pages[2]) ? $pages[2] : 'points';
                $response['method'] = $method;
                $thirtyDaysAgoTS = (new \DateTime('midnight'))->modify("-30 days");

                if (isset($_GET['advanced'])) {
                    $ags = $repo->getAggregatesForReceiver($guid, $method, $thirtyDaysAgo);
                    $response = [
                        'sum' => $ags['sum'],
                        'count' => $ags['count'],
                        'avg' => $ags['avg']
                    ];
                } else {
                    $response['sum'] = Wire\Counter::getSumByReceiver($guid, $method, $thirtyDaysAgoTS);
                }
                break;
            case "sender":
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $method = isset($pages[2]) ? $pages[2] : 'points';
                $receiver_guid = isset($pages[3]) ? $pages[3] : false;
                $response['method'] = $method;
                $thirtyDaysAgoTS = (new \DateTime('midnight'))->modify("-30 days");

                if ($receiver_guid) {
                    $response['sum'] = $repo->getSumBySenderForReceiver($guid, $receiver_guid, $method, $thirtyDaysAgoTs);
                } else {
                    $response['sum'] = $repo->getSumBySender($guid, $method, $thirtyDaysAgoTs);
                }
                break;
        }

        return Factory::response($response);
    }

    /**
     * POST
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
