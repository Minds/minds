<?php

namespace Minds\Core\Feeds\Top;

use Minds\Core\Data\ElasticSearch\Client as ElasticsearchClient;
use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Search\SortingAlgorithms;

class Repository
{
    /** @var ElasticsearchClient */
    protected $client;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @param array $opts
     * @return null
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'offset' => 0,
            'limit' => 12,
            'container_guid' => null,
            'hashtags' => [],
            'type' => null,
            'period' => null,
            'algorithm' => null,
        ], $opts);

        if (!$opts['type']) {
            throw new \Exception('Type must be provided');
        }

        if (!$opts['algorithm']) {
            throw new \Exception('Algorithm must be provided');
        }

        if (!in_array($opts['period'], ['12h', '24h', '7d', '30d', '1y'])) {
            throw new \Exception('unsupported period');
        }

        $body = [
            '_source' => [
                'guid',
            ],
            'query' => [
                'function_score' => [
                    'query' => [
                        'bool' => [
                            'must_not' => [
                                'term' => [
                                    'mature' => true,
                                ],
                            ],
                        ],
                    ],
                    "score_mode" => "sum",
                    'functions' => [
                        [
                            'filter' => [
                                'match_all' => (object) []
                            ],
                            'weight' => 1
                        ]
                    ],
                ]
            ],
            'sort' => [],
        ];

        if ($opts['container_guid']) {
            if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                $body['query']['function_score']['query']['bool']['must'] = [];
            }

            $body['query']['function_score']['query']['bool']['must'][] = [
                'match' => [
                    'container_guid' => (string) $opts['container_guid'],
                ],
            ];
        }

        //

        if ($opts['hashtags']) {
            $body['query']['function_score']['functions'][] = [
                'filter' => [
                    'multi_match' => [
                        'query' => implode(' ', $opts['hashtags']),
                        'fields' => ['message', 'tags^3']
                    ]
                ],
                'weight' => 100000000
            ];
        }

        //

        switch ($opts['algorithm']) {
            case "top":
                $algorithm = new SortingAlgorithms\Top();
                break;
            case "controversial":
                $algorithm = new SortingAlgorithms\Controversial();
                break;
            case "hot":
                $algorithm = new SortingAlgorithms\Hot();
                break;
            case "latest":
            default:
                $algorithm = new SortingAlgorithms\Chronological();
                break;
        }

        $algorithm->setPeriod($opts['period']);

        //

        $esQuery = $algorithm->getQuery();
        if ($esQuery) {
            $body['query']['function_score']['query'] = array_merge_recursive($body['query']['function_score']['query'], $esQuery);
        }

        //

        $esScript = $algorithm->getScript();
        if ($esScript) {
            $body['query']['function_score']['functions'][] = [
                'script_score' => [
                    'script' => [
                        'source' => $esScript
                    ]
                ]
            ];
        }

        //
        $esSort = $algorithm->getSort();
        if ($esSort) {
            $body['sort'][] = $esSort;
        }

        //

        $query = [
            'index' => 'minds_badger',
            'type' => $opts['type'],
            'body' => $body,
            'size' => $opts['limit'],
            'from' => $opts['offset'],
        ];

        $prepared = new Prepared\Search();
        $prepared->query($query);

        // echo(json_encode($prepared->build()['body'], JSON_PRETTY_PRINT));die;

        $response = $this->client->request($prepared);

        foreach ($response['hits']['hits'] as $doc) {
            yield [
                (string) $doc['_source']['guid'],
                $doc['_score']
            ];
        }
    }

    public function add(MetricsSync $metric)
    {
        $body = [];

        $key = $metric->getMetric() . ':' . $metric->getPeriod();
        $body[$key] = $metric->getCount();

        $body[$key . ':synced'] = $metric->getSynced();

        $query = [
            'index' => 'minds_badger',
            'type' => $metric->getType(),
            'id' => (string) $metric->getGuid(),
            'body' => ['doc' => $body],
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return $this->client->request($prepared);
    }
}
