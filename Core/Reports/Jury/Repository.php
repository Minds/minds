<?php
namespace Minds\Core\Reports\Jury;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;


class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
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
            'owner' => null
        ], $opts);


        $response = new Response;

        return $response;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Decision $decision)
    {
        $juryType = $decision->isAppeal() ? 'appeal_jury' : 'initial_jury';
        $body = [
            'script' => [
                'inline' => "ctx._source.$juryType.add(params.decision)",
                'lang' => 'painless',
                'params' => [
                    'decision' => [
                        [
                            '@timestamp' => $decision->getTimestamp(), // In MS
                            'juror_guid' => $decision->getJurorGuid(),
                            'action' => $decision->getAction(),
                        ],
                    ],
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [
                'entity_guid' => $decision->getReport()->getEntityGuid(),
                $juryType => [],
            ],
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $decision->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

}
