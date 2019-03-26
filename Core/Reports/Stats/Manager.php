<?php
/**
 * Stats
 */
namespace Minds\Core\Reports\Stats;

use Minds\Core\Di\Di;

class Manager 
{
    /** @var Client $es */
    private $es;

    /** @var array $stats */
    private $stats = [];

    /** @var Aggregates\TotalPostsAggregate $totalPostsAggregate */
    private $totalPostsAggregate;

    /** @var Aggregates\TotalAppealsAggregate $totalAppealsAggregate */
    private $totalAppealsAggregate;

    /** @var Aggregates\TotalReportsAggregate $totalReportsAggregate */
    private $totalReportsAggregate;

    /** @var Aggregates\TotalOverturnedAggregate $totalOverturnedAggregate */
    private $totalOverturnedAggregate;

    public function __construct(
        $es = null,
        $totalPostsAggregate = null,
        $totalAppealsAggregate = null,
        $totalReportsAggregate = null,
        $totalOverturnedAggregate = null
    )
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
        $this->totalPostsAggregate = $totalPostsAggregate ?: new Aggregates\TotalPostsAggregate;
        $this->totalAppealsAggregate = $totalAppealsAggregate ?: new Aggregates\TotalAppealsAggregate;
        $this->totalReportsAggregate = $totalReportsAggregate ?: new Aggregates\TotalReportsAggregate;
        $this->totalOverturnedAggregate = $totalOverturnedAggregate ?: new Aggregates\TotalOverturnedAggregate;
    }

    /**
     * @return array
     */
    public function getPublicStats()
    {
        $postsCount = (int) $this->totalPostsAggregate->get();
        $reportsCount =  (int) $this->totalReportsAggregate->get();
        $appealedCount = (int) $this->totalAppealsAggregate->get();
        $overturnedCount = (int) $this->totalOverturnedAggregate->get();

        $reportsPct = ($reportsCount / ($postsCount ?: 1)) * 100;
        $appealedPct = ($appealedCount / ($reportsCount ?: 1)) * 100;
        $upheldPct = 100 - (($overturnedCount / ($appealedCount ?: 1)) * 100);

        $this->stats = [
            'reportedPct' => round($reportsPct, 2),
            'reported' => $reportsCount,
            'appealedPct' => round($appealedPct, 2),
            'upheldPct' => $upheldPct,
        ];
        return $this->stats; 
    }

    private function getTotalPosts($period)
    {
    }

}
