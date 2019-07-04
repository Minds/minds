<?php
/**
 * User Pool for Cohort
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Generator;
use Minds\Core\Data\ElasticSearch\Client as ElasticsearchClient;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;
use Minds\Helpers\Text;

class Pool
{
    /** @var ElasticsearchClient */
    protected $elasticsearch;

    /** @var string */
    protected $index;

    /**
     * Repository constructor.
     * @param ElasticsearchClient $elasticsearch
     * @param string $index
     */
    public function __construct(
        $elasticsearch = null,
        $index = null
    )
    {
        $this->elasticsearch = $elasticsearch ?: Di::_()->get('Database\ElasticSearch');
        $this->index = $index ?: 'minds-metrics-*';
    }

    /**
     * @param array $opts
     * @return Generator
     * @yields string
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'active_threshold' => 0,
            'platform' => null,
            'for' => null,
            'except' => null,
            'except_hashes' => null,
            'include_only' => null,
            'validated' => false,
            'size' => 10,
            'page' => 0,
            'max_pages' => 20,
        ], $opts);

        $now = (int) (microtime(true) * 1000);
        $fromTimestamp = $now - ($opts['active_threshold'] * 1000);

        $body = [
            '_source' => [
                'user_guid',
            ],
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $fromTimestamp,
                                ],
                            ],
                        ],
                        [
                            'term' => [
                                'type' => 'action',
                            ],
                        ],
                    ],
                ],
            ],
            'aggs' => [
                'entities' => [
                    'terms' => [
                        'field' => 'user_guid.keyword',
                        'size' => $opts['size'],
                        'include' => [
                            'partition' => $opts['page'],
                            'num_partitions' => $opts['max_pages'],
                        ],
                    ],
                ],
            ],
            'size' => 0,
        ];

        if ($opts['platform']) {
            $body['query']['bool']['must'][] = [
                'terms' => [
                    'platform' => Text::buildArray($opts['platform']),
                ],
            ];
        }

        if ($opts['for']) {
            if (!isset($body['query']['bool']['must_not'])) {
                $body['query']['bool']['must_not'] = [];
            }

            $body['query']['bool']['must_not'][] = [
                'term' => [
                    'user_guid' => (string) $opts['for'],
                ],
            ];

            $body['query']['bool']['must_not'][] = [
                'terms' => [
                    'user_guid' => [
                        'index' => 'minds-graph',
                        'type' => 'subscriptions',
                        'id' => (string) $opts['for'],
                        'path' => 'guids',
                    ],
                ],
            ];
        }

        if ($opts['include_only']) {
            $body['query']['bool']['must'][] = [
                'terms' => [
                    'user_guid' => Text::buildArray($opts['include_only']),
                ],
            ];
        }

        if ($opts['except']) {
            if (!isset($body['query']['bool']['must_not'])) {
                $body['query']['bool']['must_not'] = [];
            }

            $body['query']['bool']['must_not'][] = [
                'terms' => [
                    'user_guid' => Text::buildArray($opts['except']),
                ],
            ];
        }

        if ($opts['except_hashes']) {
            if (!isset($body['query']['bool']['must_not'])) {
                $body['query']['bool']['must_not'] = [];
            }

            $body['query']['bool']['must_not'][] = [
                'terms' => [
                    'user_phone_number_hash' => Text::buildArray($opts['except_hashes']),
                ],
            ];
        }

        if ($opts['validated']) {
            $body['query']['bool']['must'][] = [
                'exists' => [
                    'field' => 'user_phone_number_hash',
                ],
            ];

            if (!isset($body['query']['bool']['must_not'])) {
                $body['query']['bool']['must_not'] = [];
            }

            $body['query']['bool']['must_not'][] = [
                'term' => [
                    'user_phone_number_hash' => '',
                ],
            ];
        }

        $query = [
            'index' => $this->index,
            'type' => 'action',
            'body' => $body,
        ];

        $prepared = new Search();
        $prepared->query($query);

        $result = $this->elasticsearch->request($prepared);

        $buckets = $result['aggregations']['entities']['buckets'];
        shuffle($buckets);
        echo "\n" . count($buckets) . " returned";
        foreach ($buckets as $bucket) {
            yield $bucket['key'];
        }
    }
}
