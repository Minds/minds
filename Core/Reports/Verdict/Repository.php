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
    public function add(Verdict $verdict)
    {
        $reportStage = $verdict->isAppeal() ? 'appeal_jury_' : 'initial_jury_';
        $body = [
            'doc' => [
                "@{$reportStage}decided_timestamp" => $verdict->getTimestamp(),
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

}
