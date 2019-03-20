<?php
namespace Minds\Core\Reports\Appeals;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Repository as ReportsRepository;


class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct($es = null, $reportsRepository = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
    }

    /**
     * Return a list of appeals
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner_guid' => null
        ], $opts);

        $response = new Response();

        $must = [];
        $must_not = [];

        // Only show for posts user owns
        $must[] = [
            'match' => [
                'entity_owner_guid' => (int) $opts['owner_guid'],
            ],
        ];

        // Initial jury must have acted
        $must[] = [
            'exists' => [
                'field' => '@initial_jury_decided_timestamp',
            ],
        ];

        // But not the appeal jury if we are simply reviewing
        if ($opts['showAppealed']) {
            /*$must[] = [
                'exists' => [
                    'field' => '@appeal_jury_decided_timestamp',
                ],
            ];*/
            $must[] = [
                'exists' => [
                    'field' => '@appeal_timestamp',
                ],
            ];
        } else {
            $must_not[] = [
                'exists' => [
                    'field' => '@appeal_jury_decided_timestamp',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@appeal_timestamp',
                ],
            ];
        }

        $opts['must'] = $must;
        $opts['must_not'] = $must_not;

        $reports = $this->reportsRepository->getList($opts);

        foreach ($reports as $report) {
            $appeal = new Appeal;
            $appeal
                ->setTimestamp($report->getAppealTimestamp())
                ->setReport($report)
                ->setNote($report->getAppealNote());
            $response[] = $appeal;
        }
        return $response;
    }

    /**
     * Add an appeal
     * @param Appeal $appeal
     * @return boolean
     */
    public function add(Appeal $appeal)
    {
        $body = [
            'doc' => [
                '@appeal_timestamp' => (int) $appeal->getTimestamp(),
                'appeal_note' => $appeal->getNote(),
            ],
            'doc_as_upsert' => true,
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $appeal->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

}
