<?php
/**
 * Minds Admin: Analytics
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin\analytics;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Core\Boost\Network;
use Minds\Api\Factory;

class boost implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Return analytics data
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];

        $analytics = new Network\Analytics;

        $boosts = [
          'review' =>  $analytics->getReviewCount(),
          'review_backlog' => $analytics->getReviewBacklog(),
          'approved' => $analytics->getApprovedCount(),
          'approved_backlog' => $analytics->getApprovedBacklog(),
          'impressions' => $analytics->getImpressions(),
          'impressions_met' => $analytics->getImpressionsMet(),
        ];

        /*$boosts_content = [
          'approved' => $mongo->count("boost", $boost_objs_query),
          'impressions' => $boost_impressions,
          'impressions_met' => $boost_impressions_met
        ];*/

        $response['newsfeed'] = $boosts;
        $response['content'] = $boosts_content;

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }
}
