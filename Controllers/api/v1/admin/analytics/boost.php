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

        $mongo = Core\Data\Client::build('MongoDB');
        $boost_impressions = 0;
        $boost_impressions_met = 0;
        $boost_backlog = null;
        $boost_objs = $mongo->find("boost", [
            'state'=>'approved',
            'type'=>'newsfeed',
        ], [
            'sort' => [ '_id'=> 1 ],
        ]);
        foreach ($boost_objs as $boost) {
            if ($boost_backlog == null) {
                $boost_backlog = (time() - $boost['_id']->getTimestamp()) / (60 * 60);
            }
            $boost_impressions = $boost_impressions + $boost['impressions'];
            $boost_impressions_met = $boost_impressions_met + Helpers\Counters::get((string) $boost['_id'], "boost_impressions", false);
        }

        $boost_reviews = $mongo->find("boost", [
            'state'=>'review',
            'type'=>'newsfeed',
        ], [
            'sort' => [ '_id' => 1 ],
        ]);
        foreach ($boost_reviews as $obj) {
            $review_backlog = (time() - $obj['_id']->getTimestamp()) / (60 * 60);
            break;
        }
        $boosts = [
          'review' =>  $boost_reviews->count(),
          'review_backlog' => $review_backlog,
          'approved' => $boost_objs->count(),
          'approved_backlog' => $boost_backlog,
          'impressions' => $boost_impressions,
          'impressions_met' => $boost_impressions_met
        ];

        $boost_objs = $mongo->find("boost", ['state'=>'approved', 'type'=>'content']);
        $boost_impressions = 0;
        $boost_impressions_met = 0;
        foreach ($boost_objs as $boost) {
            $boost_impressions = $boost_impressions + $boost['impressions'];
            $boost_impressions_met = $boost_impressions_met + Helpers\Counters::get((string) $boost['_id'], "boost_impressions", false);
        }
        $boosts_content = [
          'approved' => $boost_objs->count(),
          'impressions' => $boost_impressions,
          'impressions_met' => $boost_impressions_met
        ];

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
