<?php
namespace Minds\Core\Reports\Jury;

use Cassandra;
use Cassandra\Type;
use Cassandra\Map;
use Cassandra\Set;
use Cassandra\Bigint;
use Cassandra\Tinyint;
use Cassandra\Decimal;
use Cassandra\Timestamp;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Core\Reports\Report;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Repository as ReportsRepository;


class Repository
{
    /** @var Data\Cassandra\Client $cql */
    protected $cql;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct($cql = null, $reportsRepository = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
    }

    /**
     * Return the decisions a jury has made
     * @param array $options 'limit', 'offset', 'state'
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null,
            'juryType' => 'appeal',
            'user' => null,
        ], $opts);

        if (!$opts['user']->getPhoneNumberHash()) {
            return null;
        }

        $statement = "SELECT * FROM moderation_reports_by_state
            WHERE state = ?";

        $values = [
            $opts['juryType'] === 'appeal' ? 'appealed' : 'reported',
        ];

        $prepared = new Prepared;
        $prepared->query($statement, $values);

        $result = $this->cql->request($prepared);

        $response = new Response;

        foreach ($result as $row) {
            if ($row['user_hashes']
                && in_array($opts['user']->getPhoneNumberHash(), 
                    array_map(function ($hash) {
                        return $hash;
                    }, $row['user_hashes']->values())
                )
            ) {
                continue; // Already interacted with
            }

            $report = $this->reportsRepository->buildFromRow($row);

            $response[] = $report;
        }

        return $response;
    }

    /**
     * Return a single report
     * @param string $urn
     * @return Report
     */
    public function get($urn)
    {
        // TODO: Do not return if we no longer meet criteria
        return $this->reportsRepository->get($urn);
    }


    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Decision $decision)
    {
        $statement = "UPDATE moderation_reports
            SET initial_jury += ?,
            user_hashes += ?
            WHERE entity_urn = ?
            AND reason_code = ?
            AND sub_reason_code = ?
            AND timestamp = ?";

        if ($decision->isAppeal()) {
            $statement = "UPDATE moderation_reports
                SET appeal_jury += ?,
                user_hashes += ?
                WHERE entity_urn = ?
                AND reason_code = ?
                AND sub_reason_code = ?
                AND timestamp = ?";
        }

        $map = new Map(Type::bigint(), Type::boolean());
        $map->set(new Bigint($decision->getJurorGuid()), $decision->isUpheld());

        $set = new Set(Type::text());
        $set->add($decision->getJurorHash() ?? 'testing');
        $params = [
            $map,
            $set,
            $decision->getReport()->getEntityUrn(),
            new Tinyint($decision->getReport()->getReasonCode()),
            new Decimal($decision->getReport()->getSubReasonCode()),
            new Timestamp($decision->getReport()->getTimestamp()),
        ];

        $prepared = new Prepared();
        $prepared->query($statement, $params);

        return (bool) $this->cql->request($prepared);
    }

}
