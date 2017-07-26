<?php
/**
 * Minds Admin: Boost Analytics
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin\boosts;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class analytics implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * GET
     */
    public function get($pages)
    {
        $response = [];

        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Specify a boost handler type'
            ]);
        }

        $handler = Core\Boost\Factory::build($pages[0]);
        $cache = Di::_()->get('Cache');

        $cacheKey = "admin:boosts:analytics:{$pages[0]}";

        if ($cached = $cache->get($cacheKey)) {
            return Factory::response($cached);
        }

        $reviewQueue = $handler->getReviewQueueCount();

        $backlog = $handler->getBacklogCount();
        $priorityBacklog = $handler->getPriorityBacklogCount();

        $impressions = $handler->getBacklogImpressionsSum();

        $avgApprovalTime = $handler->getAvgApprovalTime();
        $avgImpressions = round($impressions / ($backlog ?: 1));

        $timestamp = time();

        $response = compact(
            'reviewQueue',
            'backlog',
            'priorityBacklog',
            'impressions',
            'avgApprovalTime',
            'avgImpressions',
            'timestamp'
        );

        $cache->set($cacheKey, $response, 15 * 60 /* 15min cache */);

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
