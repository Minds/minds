<?php
namespace Minds\Core\Reports\Stats\Aggregates;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;
use Cassandra\Timestamp;

class TotalAppealsAggregate implements ModerationStatsAggregateInterface
{
    /** @var Client $cql */
    private $cql;

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @return init
     */
    public function get(): int
    {
        $statement = "SELECT count(*) as total FROM moderation_reports_by_state
            WHERE state IN ('appealed', 'appeal_jury_decided')
              AND timestamp > ?";
        $values = [ new Timestamp(strtotime('-30 days', time())) ];

        $prepared = new Prepared\Custom();
        $prepared->query($statement, $values);
        $result = $this->cql->request($prepared);

        return (int) $result[0]['total']->value();
    }

}
