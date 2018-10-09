<?php
/**
 * Limits map
 */
namespace Minds\Core\Security\RateLimits;

use Minds\Core\Trending\Aggregates; //TODO: This should probably be more unified

class Maps
{

    public static $maps = [
        'interaction:subscribe' => [
            'period' => 300, //5 minutes
            'threshold' => 50, //50 per 5 minutes, 10 per minute
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
        'interaction:subscribehour' => [
            'period' => 3600, //1 hour
            'threshold' => 200,
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
        'interaction:subscribeday' => [
            'period' => 86400, //1 day
            'threshold' => 400,
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
    ];

}