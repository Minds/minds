<?php
namespace Minds\Core\Reports\Verdict;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Repository as ReportsRepository;


class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
    }

    /**
     * Return the decisions a jury has made
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null,
            'juryType' => 'initial',
        ], $opts);

        $must = [];
        $must_not = [];

        if ($opts['entity_guid']) {
            $must[] = [
                'match' => [
                    'entity_guid' => $opts['entity_guid'],
                ],
            ];
        }

        if (!in_array($opts['juryType'], [ 'initial', 'appeal' ])) {
            throw new \Exception('Jury type must be initial or appeal');
        }

        if ($opts['juryType'] === 'initial') {
            $must_not[] = [
                'exists' => [
                    'field' => '@initial_jury_decided_timestamp',
                ],
            ];
        } else {
            $must[] = [
                'exists' => [
                    'field' => '@initial_jury_decided_timestamp',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@appeal_jury_decided_timestamp',
                ],
            ];
        }

        $response = new Response;

        $opts['must'] = $must;
        $opts['must_not'] = $must_not;

        $reports = $this->reportsRepository->getList($opts);

        foreach ($reports as $report) {
            $verdict = new Verdict();
            $verdict->setReport($report);

            $response[] = $verdict;
        }

        return $response;
    }

    /**
     * @param $entity_guid
     * @return Verdict
     */
    public function get($entity_guid)
    {
        $response = $this->getList([
            'entity_guid' => $entity_guid,
        ]);

        if ($response[0] 
            && $response[0]->getReport()
            && $response[0]->getReport()->getEntityGuid() == $entity_guid
        ) {
            return $response[0];
        }

        return null;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Verdict $verdict)
    {
        $reportStage = $verdict->isAppeal() ? 'appeal_jury_' : 'initial_jury_';
        $body = [
            'doc' => [
                "@{$reportStage}decided_timestamp" => (int) $verdict->getTimestamp(),
                $reportStage . 'action' => $verdict->getAction(),
            ],
            'doc_as_upsert' => true,
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $verdict->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

    private function buildDecisions($row)
    {
        $jurorGuids = [];

        $return = [];

        $reports = $row['_source']['@initial_jury_decided_timestamp'] 
            ? $row['_source']['appeal_jury']
            : $row['_source']['initial_jury'];

        foreach ($reports as $row) {
            if (!isset($row[0]['action'])) {
                continue; // Something didn't save properly
            }
            if (isset($jurorGuids[$row[0]['juror_guid']])) {
                continue; // avoid duplicate reports
            }
            $jurorGuids[$row[0]['juror_guid']] = true; 

            $decision = new Decision();
            $decision
                ->setTimestamp($row[0]['@timestamp'])
                ->setEntityGuid($row['_source']['entity_guid'])
                ->setJurorGuid($row[0]['juror_guid'])
                ->setAction($row[0]['action']);
            $return[] = $decision;
        }
        return $return;
    }

}
