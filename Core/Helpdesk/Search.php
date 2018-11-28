<?php
/**
 * Help & Support Group posts search
 */
namespace Minds\Core\Helpdesk;

use Minds\Core;
use Minds\Core\Di\Di;

class Search
{
    /**
     * Constructor
     *
     * @param Database\ElasticSearch $elastic
     * @param string $index
     */
    public function __construct($elastic = null, $index = null, $entitiesBuilder = null)
    {
        $this->elastic = $elastic ?: Di::_()->get('Database\ElasticSearch');
        $this->index = $index ?: Di::_()->get('Config')->elasticsearch['index'];
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * Search
     *
     * @param string $string
     * @param integer $limit
     * @return array Activities
     */
    public function search($string, $limit = 5)
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => [
                                'container_guid' => '100000000000000681'
                            ]
                        ],
                        [
                            'query_string' => [
                                'default_field' => 'message',
                                'query' => $string
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query([
            'body' => $body,
            'index' => $this->index,
            'type' => 'activity',
            'size' => $limit,
            'from' => (int) $this->offset,
            'client' => [
                'timeout' => 2,
                'connect_timeout' => 1
            ]
        ]);

        $result = $this->elastic->request($prepared);

        if (!isset($result['hits'])) return [];

        $entitiesBuilder = $this->entitiesBuilder;

        $entities = array_map(function($r) use ($entitiesBuilder) {
            return $entitiesBuilder->single($r['_source']['guid']);
        }, $result['hits']['hits']);

        $entities = array_values(array_filter($entities));

        return $entities;
    }
}
