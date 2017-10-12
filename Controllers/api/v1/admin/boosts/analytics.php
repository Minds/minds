<?php
/**
 * Minds Admin: Boost Analytics
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin\boosts;

use Minds\Controllers\api\v1\newsfeed\preview;
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

        $type = isset($pages[0]) ? $pages[0] : 'newsfeed';

        /** @var Core\Boost\Network\Review $review */
        $review = Di::_()->get('Boost\Network\Review');
        $review->setType($type);
        /** @var Core\Boost\Network\Metrics $metrics */
        $metrics = Di::_()->get('Boost\Network\Metrics');
        $metrics->setType($type);
        $cache = Di::_()->get('Cache');

        $cacheKey = "admin:boosts:analytics:{$type}";

        if ($cached = $cache->get($cacheKey)) {
            return Factory::response($cached);
        }

        $reviewQueue = $review->getReviewQueueCount();

        $backlog = $metrics->getBacklogCount();
        $priorityBacklog = $metrics->getPriorityBacklogCount();

        $impressions = $metrics->getBacklogImpressionsSum();

        $avgApprovalTime = $metrics->getAvgApprovalTime();
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
