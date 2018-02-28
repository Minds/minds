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
        /** @var Wire\Sums $sums */
        $sums = Core\Di\Di::_()->get('Wire\Sums');

        switch ($pages[0]) {
            case 'overview':
                $guid = isset($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;
                $timestamp = isset($_GET['start']) ? ((int) $_GET['start']) : (new \DateTime('midnight'))->modify("-30 days")->getTimestamp();

                $sums->setFrom($timestamp)
                    ->setReceiver($guid);

                $isSelf = Core\Session::getLoggedInUser()->guid == $guid;
                $cache = Di::_()->get('Cache');
                $cacheKey = "wire:sums:overview:{$guid}";

                if (!$isSelf && ($cached = $cache->get($cacheKey))) {
                    return Factory::response($cached);
                }

                $response = [
                    'tokens' => 0,
                    'tokens_count' => 0,
                    'tokens_avg' => 0,
                ];

                $tokens = $sums->getAggregates();

                $response['count'] += $tokens['count'];

                $response = array_merge($response, [
                    'tokens' => $tokens['sum'],
                    'tokens_count' => $tokens['count'],
                    'tokens_avg' => $tokens['avg'],
                ]);
                
                $cache->set($cacheKey, $response, 6 * 60 * 60 /* 6 hours cache */);

                break;
            case "receiver":
                $timestamp = isset($_GET['start']) ? ((int) $_GET['start']) : (new \DateTime('midnight'))->modify("-30 days")->getTimestamp();

                $sums->setFrom($timestamp)
                    ->setReceiver(Core\Session::getLoggedInUser());

                if (isset($_GET['advanced'])) {
                    $ags = $sums->getAggregates();
                    $response = [
                        'sum' => $ags['sum'],
                        'count' => $ags['count'],
                        'avg' => $ags['avg']
                    ];
                } else {
                    $response['sum'] = Wire\Counter::getSumByReceiver(Core\Session::getLoggedInUser()->guid, 'token', $timestamp);
                }
                break;
            case "sender":
                $receiver_guid = isset($pages[3]) ? $pages[3] : false;
                $thirtyDaysAgoTS = (new \DateTime('midnight'))->modify("-30 days");

                $sums->setFrom($thirtyDaysAgoTS)
                    ->setSender(Core\Session::getLoggedInUser())
                    ->setReceiver($receiver_guid);

                $response['sum'] = $sums->getSent();
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
