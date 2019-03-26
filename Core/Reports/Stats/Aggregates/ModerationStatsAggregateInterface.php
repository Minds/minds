<?php
namespace Minds\Core\Reports\Stats\Aggregates;

interface ModerationStatsAggregateInterface
{

    /**
     * @return int
     */
    public function get(): int;

}
