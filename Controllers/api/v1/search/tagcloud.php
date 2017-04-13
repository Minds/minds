<?php
/**
 * Minds Core Search Tag Cloud API
 *
 * @version 1
 * @author Mar Harding
 */
namespace Minds\Controllers\api\v1\search;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Search\Documents;

class tagcloud implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {

        return Factory::response([]);

        $response = [];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $timestamps = Core\Analytics\Timestamps::span(1, 'day');

        $cache = Core\Di\Di::_()->get('Cache\Apcu');
        if ($cached = $cache->get('trending:hashtags')) {
            $result = json_decode($cached, true);
        } else {
            $opts = [
                'index' => 'minds',
                'type' => 'activity',
                'search_type' => 'count',
                'body' => [
                    'query' => [
                        'range' => [
                            'time_created' => [
                                'gte' => $timestamps[0]
                             ]
                         ]
                    ],
                    'aggs' => [
                        'minds' => [
                            'terms' => [
                                'field' => "hashtags",
                                'size' => $limit
                            ]
                        ]
                    ]
                ]
            ];

            $result = (new Documents())->customQuery($opts);
            $cache->set('trending:hashtags', json_encode($result), (60*60));
        }        

        if($result){
            $tags = [];

            foreach ($result['aggregations']['minds']['buckets'] as $tag) {
              $tags[] = $tag['key'];
            }

            $response['tags'] = $tags;
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
