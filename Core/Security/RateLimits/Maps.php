<?php
/**
 * Limits map
 */

namespace Minds\Core\Security\RateLimits;

use Minds\Core\Security\RateLimits\Aggregates\Comments;
use Minds\Core\Security\RateLimits\Aggregates\Reminds;
use Minds\Core\Security\RateLimits\Aggregates\VotesDown;
use Minds\Core\Security\RateLimits\Aggregates\VotesUp;
use Minds\Core\Trending\Aggregates; //TODO: This should probably be more unified

class Maps
{

    public static $maps = [
        'interaction:subscribe' => [
            'interaction' => 'subscribe',
            'period' => 300, //5 minutes
            'threshold' => 50, //50 per 5 minutes, 10 per minute
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
        'interaction:subscribehour' => [
            'interaction' => 'subscribe',
            'period' => 3600, //1 hour
            'threshold' => 200,
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
        'interaction:subscribeday' => [
            'interaction' => 'subscribe',
            'period' => 86400, //1 day
            'threshold' => 400,
            'aggregates' => [
                Aggregates\Subscribe::class,
            ],
        ],
        'interaction:voteup' => [
            'interaction' => 'voteup',
            'period' => 300, //5 minutes
            'threshold' => 150, //150 per 5 minutes, 10 per minute
            'aggregates' => [
                VotesUp::class,
            ],
        ],
        'interactions:voteupday' => [
            'interaction' => 'voteup',
            'period' => 86400, //1 day
            'threshold' => 1000,
            'aggregates' => [
                VotesUp::class,
            ],
        ],
        'interaction:votedown' => [
            'interaction' => 'votedown',
            'period' => 300, //5 minutes
            'threshold' => 10, //150 per 5 minutes, 10 per minute
            'aggregates' => [
                VotesDown::class,
            ],
        ],
        'interactions:votedownday' => [
            'interaction' => 'votedown',
            'period' => 86400, //1 day
            'threshold' => 100,
            'aggregates' => [
                VotesDown::class,
            ],
        ],
        'interaction:comment' => [
            'interaction' => 'comment',
            'period' => 300, //5 minutes
            'threshold' => 75, //150 per 5 minutes, 10 per minute
            'aggregates' => [
                Comments::class,
            ],
        ],
        'interactions:commentday' => [
            'interaction' => 'comment',
            'period' => 86400, //1 day
            'threshold' => 500,
            'aggregates' => [
                Comments::class,
            ],
        ],
        'interactions:remindday' => [
            'interaction' => 'remind',
            'period' => 86400, //1 day
            'threshold' => 500,
            'aggregates' => [
                Reminds::class,
            ],
        ]
    ];

}
