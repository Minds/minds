<?php
namespace Minds\Core\Reports\UserReports;

use Cassandra;
use Cassandra\Type;
use Cassandra\Bigint;
use Cassandra\Float_ as FloatInt;
use Cassandra\Type\Set;
use Cassandra\Timestamp;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;


class Repository
{
    /** @var Data\Cassandra\Client $es */
    protected $cql;

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null
        ], $opts);

    }

    /**
     * Add a report for an entity
     * @param Report $report
     * @return boolean
     */
    public function add(UserReport $report)
    {
        $statement = "UPDATE moderation_reports
            SET reports += ?,
                user_hashes += ?,
            WHERE entity_urn = ?
            AND reason_code = ?
            AND sub_reason_code = ?
            AND timestamp = ?";
        
        $prepared = new Prepared;
        $prepared->query($statement, [
                (new Set(Type::bigint()))
                    ->set($report->getReporterGuid()),
                (new Set(Type::bigint()))
                    ->set($report->getReporterHash()),
                $report->getReport()->getEntityUrn(),
                new FloatInt($report->getReasonCode()),
                new FloatInt($report->getSubReasonCode()),
                new Timestamp($report->getReport()->getTimestamp()),
            ]);

        return (bool) $this->cql->request($prepared);
    }

}
