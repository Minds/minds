<?php
namespace Minds\Core\Feeds\Top;

use Minds\Core\Trending\Aggregates;

class Maps
{

    public static $maps = [
        'newsfeed' => [
            'type' => 'activity',
            'subtype' => '',
            'aggregates' => [
//                Aggregates\Comments::class,
                Aggregates\Votes::class,
                Aggregates\DownVotes::class,
//                Aggregates\Reminds::class
            ]
        ],
        'videos' => [
            'type' => 'object',
            'subtype' => 'video',
            'aggregates' => [
                Aggregates\Comments::class,
                Aggregates\Votes::class,
                Aggregates\Reminds::class
            ]
        ],
        'images' => [
            'type' => 'object',
            'subtype' => 'image',
            'aggregates' => [
                Aggregates\Comments::class,
                Aggregates\Votes::class,
                Aggregates\Reminds::class
            ]
        ],
        'blogs' => [
            'type' => 'object',
            'subtype' => 'blog',
            'aggregates' => [
                Aggregates\Comments::class,
                Aggregates\Votes::class,
                Aggregates\Reminds::class
            ]
        ],
        'groups' => [
            'type' => 'group',
            'subtype' => '',
            'aggregates' => [
                Aggregates\Joins::class,
                Aggregates\Comments::class,
                //Aggregates\Posts::class,
                Aggregates\Reminds::class,
                Aggregates\Votes::class,
            ]
        ],
        'channels' => [
            'type' => 'user',
            'subtype' => '',
            'aggregates' => [
                Aggregates\ChannelVotes::class,
                Aggregates\Subscriptions::class
            ]
        ]
    ];

}
