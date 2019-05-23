<?php
namespace Minds\Core\Reports\UserReports;

use Cassandra;
use Cassandra\Type;
use Cassandra\Tinyint;
use Cassandra\Bigint;
use Cassandra\Decimal;
use Cassandra\Set;
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
            state = 'reported',
            entity_owner_guid = ?";
        
        $set = new Set(Type::bigint());
        $set->add(new Bigint($report->getReporterGuid()));
        $values = [
            $set,
            new Bigint($report->getReport()->getEntityOwnerGuid()),
        ];

        if ($report->getReporterHash()) {
            $statement .= ", user_hashes += ?";
            $hashesSet = new Set(Type::text());
            $hashesSet->add($report->getReporterHash());
            $values[] = $hashesSet; 
        }

        $statement .= " WHERE entity_urn = ?
            AND reason_code = ?
            AND sub_reason_code = ?
            AND timestamp = ?";
        $values[] = $report->getReport()->getEntityUrn();
        $values[] = new Tinyint($report->getReport()->getReasonCode());
        $values[] = new Decimal($report->getReport()->getSubReasonCode() ?? 0);
        $values[] = new Timestamp($report->getReport()->getTimestamp());
        
        $prepared = new Prepared;
        $prepared->query($statement, $values);

        return (bool) $this->cql->request($prepared);
    }

}
