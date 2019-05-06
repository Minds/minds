<?php
namespace Minds\Core\Reports\Jury;

use Cassandra;
use Cassandra\Type;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Core\Reports\Report;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Repository as ReportsManager;


class Repository
{
    /** @var Data\Cassandra\Client $cql */
    protected $cql;

    /** @var ReportsManager $reportsManager */
    private $reportsManager;

    public function __construct($cql = null, $reportsManager = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Client');
        $this->reportsManager = $reportsManager ?: new ReportsManager;
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
            if (in_array($opts['user']->getPhoneNumberHash(), 
                    array_map(function ($hash) {
                        return $hash;
                    }, $row['user_hashes']->values())
                )
            ) {
                continue; // Already interacted with
            }

            $report = new Report();
            $report->setEntityUrn($row['entity_urn'])
                ->setReasonCode($row['reason_code']->value())
                ->setSubReasonCode($row['sub_reason_code']->value());
                
            $response[] = $report;
        }

        return $response;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Decision $decision)
    {
        $statement = "UPDATE moderation_reports
            SET initial_jury += ?
            SET user_hashes += ?
            WHERE entity_urn = ?
            AND reason_code = ?
            AND sub_reason_code = ?";

        if ($decision->isAppeal()) {
            $statement = "UPDATE moderation_reports
                SET appeal_jury += ?
                SET user_hashes += ?
                WHERE entity_urn = ?
                AND reason_code = ?
                AND sub_reason_code = ?";
        }

        $params = [
            (new Type\Map(Type::bigint(), Type::boolean()))
                ->set($decision->getJurorGuid(), $decision->isUpheld()),
            (new Type\Set(Type::text()))
                ->set($decision->getJurorHash()),
            $decision->getReport()->getEntityUrn(),
            (float) $decision->getReport()->getReasonCode(),
            (float) $decision->getReport()->getSubReasonCode(),
        ];

        $prepared = new Prepared();
        $prepared->query($statement, $params);

        return (bool) $this->cql->request($prepared);
    }

}
