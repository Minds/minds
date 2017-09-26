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
            case 'overview':
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $timestamp = isset($_GET['start']) ? ((int) $_GET['start']) : (new \DateTime('midnight'))->modify("-30 days")->getTimestamp();
                $merchant = isset($_GET['merchant']) ? (bool) $_GET['merchant'] : (bool) (new User($guid))->getMerchant();

                $isSelf = Core\Session::getLoggedInUser()->guid == $guid;
                $cache = Di::_()->get('Cache');
                $cacheKey = "wire:sums:overview:{$guid}";

                if (!$isSelf && ($cached = $cache->get($cacheKey))) {
                    return Factory::response($cached);
                }

                $points = $repo->getAggregatesForReceiver($guid, 'points', $timestamp);

                $response = [
                    'count' => $points['count'],

                    'points' => $points['sum'],
                    'points_count' => $points['count'],
                    'points_avg' => $points['avg'],

                    'money' => 0,
                    'money_count' => 0,
                    'money_avg' => 0,
                ];

                if ($merchant) {
                    $money = $repo->getAggregatesForReceiver($guid, 'money', $timestamp);

                    $response['count'] += $money['count'];

                    $response = array_merge($response, [
                        'money' => $money['sum'],
                        'money_count' => $money['count'],
                        'money_avg' => $money['avg'],
                    ]);
                }

                $cache->set($cacheKey, $response, 6 * 60 * 60 /* 6 hours cache */);

                break;
            case "receiver":
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $method = isset($pages[2]) ? $pages[2] : 'points';
                $response['method'] = $method;
                $timestamp = isset($_GET['start']) ? ((int) $_GET['start']) : (new \DateTime('midnight'))->modify("-30 days")->getTimestamp();

                if (isset($_GET['advanced'])) {
                    $ags = $repo->getAggregatesForReceiver($guid, $method, $timestamp);
                    $response = [
                        'sum' => $ags['sum'],
                        'count' => $ags['count'],
                        'avg' => $ags['avg']
                    ];
                } else {
                    $response['sum'] = Wire\Counter::getSumByReceiver($guid, $method, $timestamp);
                }
                break;
            case "sender":
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $method = isset($pages[2]) ? $pages[2] : 'points';
                $receiver_guid = isset($pages[3]) ? $pages[3] : false;
                $response['method'] = $method;
                $thirtyDaysAgoTS = (new \DateTime('midnight'))->modify("-30 days");

                if ($receiver_guid) {
                    $response['sum'] = $repo->getSumBySenderForReceiver($guid, $receiver_guid, $method, $thirtyDaysAgoTS);
                } else {
                    $response['sum'] = $repo->getSumBySender($guid, $method, $thirtyDaysAgoTS);
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
