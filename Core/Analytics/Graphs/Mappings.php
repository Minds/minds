<?php

namespace Minds\Core\Analytics\Graphs;

class Mappings
{
    private $mappings = [
        'avgpageviews' => Aggregates\AvgPageviews::class,
        'interactions' => Aggregates\Interactions::class,
        'offchainboosts' => Aggregates\OffchainBoosts::class,
        'onchainboosts' => Aggregates\OnchainBoosts::class,
        'offchainplus' => Aggregates\OffchainPlus::class,
        'onchainplus' => Aggregates\OnchainPlus::class,
        'offchainwire' => Aggregates\OffchainWire::class,
        'onchainwire' => Aggregates\OnchainWire::class,
        'activeusers' => Aggregates\ActiveUsers::class,
        'posts' => Aggregates\Posts::class,
        'votes' => Aggregates\Votes::class,
        'comments' => Aggregates\Comments::class,
        'reminds' => Aggregates\Reminds::class,
        'subscribers' => Aggregates\Subscribers::class,
        'totalpageviews' => Aggregates\TotalPageviews::class,
        'usersegments' => Aggregates\UserSegments::class,
        'pageviews' => Aggregates\Pageviews::class,
        'withdraw' => Aggregates\Withdraw::class,
        'tokensales' => Aggregates\TokenSales::class,
        'rewards' => Aggregates\Rewards::class,
    ];

    public function getMapping($aggregate)
    {
        return $this->mappings[$aggregate] ? new $this->mappings[$aggregate] : null;
    }
}
