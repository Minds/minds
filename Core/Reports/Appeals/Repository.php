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


class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
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
            'owner' => null
        ], $opts);

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
                '@appeal_timestamp' => $appeal->getTimestamp(),
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
