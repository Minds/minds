<?php
namespace Minds\Core\Reports\Verdict;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Repository as ReportsRepository;


class Repository
{
    /** @var Data\Cassandra\Client $cql */
    protected $cql;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct($cql = null, $reportsRepository = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Client');
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
    }

    /**
     * Return the decisions a jury has made
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getList(array $opts = [])
    {
        return new Response;
    }

    /**
     * @param $entity_guid
     * @return Verdict
     */
    public function get($entity_guid)
    {
        return null;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Verdict $verdict)
    {

        $statement = "UPDATE moderation_reports
            SET state = ?
            WHERE entity_urn = ?
            AND reason_code = ?
            AND sub_reason_code = ?";

        $values = [
            $verdict->isAppeal() ? 'appeal_jury_decided' : 'initial_jury_decided',
            $verdict->getReport()->getEntityUrn(),
            $verdict->getReport()->getReasonCode(),
            $verdict->getReport()->getSubReasonCode(),
        ];

        $prepared = new Prepared;
        $prepared->query($statement, $values);

        return (bool) $this->cql->request($prepared);
    }

}
