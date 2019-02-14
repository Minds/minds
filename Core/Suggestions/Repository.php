<?php
/**
 */
namespace Minds\Core\Suggestions;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Core\Data\ElasticSearch\Prepared\Search as Prepared;

class Repository
{

    /** @var $es */
    private $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return a list
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => 0,
            'user_guid' => null,
            'paging-token' => '',
        ], $opts);

        if ($opts['offset']) {
            $opts['limit'] += $opts['offset'];
        }

        $must = [ ];
        $must_not = [];

        // Terms lookup against minds-graph:subscrpitions
        $must[]['terms'] = [
            'user_guid.keyword' => [
                'index' => 'minds-graph',
                'type' => 'subscriptions',
                'id' => $opts['user_guid'],
                'path' => 'guids',
            ],
        ];

        // Check subscribers action
        $must[]['term'] = [
            'action.keyword' => 'subscribe',
        ];

        // Range
        $must[]['range'] = [
            '@timestamp' => [
                'gte' => strtotime('midnight -30 days', time()) * 1000,
                'lt' => strtotime('midnight', time()) * 1000,
            ],
        ];

        // Remove everyone we are subscribe to already
        $must_not[]['terms'] = [
            'entity_guid.keyword' => [
                'index' => 'minds-graph',
                'type' => 'subscriptions',
                'id' => $opts['user_guid'],
                'path' => 'guids',
            ],
        ];

        // Remove ourselves
        $must_not[]['term'] = [
            'entity_guid.keyword' => $opts['user_guid'],
        ];

        // Remove everyone we have passed
        $must_not[]['terms'] = [
            'entity_guid.keyword' => [
                'index' => 'minds-graph',
                'type' => 'pass',
                'id' => $opts['user_guid'],
                'path' => 'guids',
            ],
        ];

        $query = [
            'index' => 'minds-metrics-*',
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
                    ],
                ],
                'aggs' => [
                    'subscriptions' => [
                        'terms' => [
                            'field' => 'entity_guid.keyword',
                            'size' => $opts['limit'],
                            'order' => [
                                '_count' =>  'desc',
                            ], 
                        ],
                    ],
                ],
            ],
        ];
        
        $prepared = new Prepared();
        $prepared->query($query);

        $result = $this->es->request($prepared);

        $response = new Response();

        foreach ($result['aggregations']['subscriptions']['buckets'] as $i => $row) {
            if ($i < $opts['offset'] -1 || count($response) >= $opts['limit'] - $opts['offset']) {
                continue;
            }
            $suggestion = new Suggestion();
            $suggestion->setConfidenceScore($row['doc_count'])
                ->setEntityGuid($row['key'])
                ->setEntityType('user');
            $response[] = $suggestion;
        }
        
        return $response;
    }

    /**
     * Return a single suggestion
     * @return Suggestion
     */
    public function get($guid)
    {
    }

    public function add($suggestion)
    {
    }

    public function update($suggestion)
    {

    }


    public function delete($suggestion)
    {
    }

}
