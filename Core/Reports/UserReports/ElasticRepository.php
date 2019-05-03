<?php
namespace Minds\Core\Reports\UserReports;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;


class ElasticRepository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
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
        $body = [
            'script' => [
                'inline' => 'ctx._source.reports.add(params.report)',
                'lang' => 'painless',
                'params' => [
                    'report' => [
                        [
                            '@timestamp' => (int) $report->getTimestamp(), // In MS
                            'reporter_guid' => $report->getReporterGuid(),
                            'reason' => $report->getReasonCode(),
                            'sub_reason' => (int) $report->getSubReasonCode(),
                        ],
                    ],
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [
                'entity_guid' => $report->getReport()->getEntityGuid(),
                'entity_owner_guid' => $report->getReport()->getEntityOwnerGuid(),
                'reports' => []
            ],
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $report->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

}
