<?php

namespace Minds\Core\Hashtags\Trending;

use Cassandra\Timestamp;
use Cassandra\Bigint;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Data\ElasticSearch\Client as ElasticSearchClient;
use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared\Search as Prepared;
use Minds\Common\Repository\Response;

/**
 * Hashtags Trending Repository
 */
class Repository
{
    /** @var CassandraClient $db */
    protected $db;

    /** @var ElasticSearchClient $es */
    protected $es;

    public function __construct($db = null, $es = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'from' => strtotime('-24 hours', time()),
        ], $opts);

        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'votes:up:24h:synced' => [
                                    'gte' => $opts['from'],
                                ],
                            ],
                        ],
                    ],
                    'must_not' => [
                        'bool' => [
                            'should' => [
                                [
                                    'terms' => [
                                        'nsfw' => [ 1, 2, 3, 4, 5, 6 ],
                                    ],
                                ],
                                [
                                    'terms' => [
                                        'tags' => array_column($this->getHidden(), 'hashtag'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'aggs' => [
                'tags' => [
                    'terms' => [
                        'field' => 'tags.keyword',
                        'size' => $opts['limit'] * 20,
                        'order' => [
                            'counts' => 'desc',
                        ],
                    ],
                    'aggs' => [
                        'counts' => [
                            'max' => [
                                'field' => 'votes:up:24h',
                            ],
                        ],
                        'owners' => [
                            'cardinality' => [
                                'field' => 'owner_guid.keyword',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $query = [
            'index' => 'minds_badger',
            'type' => 'activity',
            'body' => $body,
            'size' => 0,
        ];

        $prepared = new Prepared();
        $prepared->query($query);

        $result = $this->es->request($prepared);

        $response = new Response();

        $rows = $result['aggregations']['tags']['buckets'];

        usort($rows, function($a, $b) {
            $a_score = $this->getConfidenceScore($a['owners']['value'], $a['doc_count']);
            $b_score = $this->getConfidenceScore($b['owners']['value'], $b['doc_count']);     

            return $a_score < $b_score ? 1 : 0;
        });

        foreach ($rows as $row) {
            $response[] = $row['key'];
            if (count($response) > $opts['limit']) {
                break;
            }
        }
        return $response;
    }

    /**
     * Return a confidence score
     * TODO: Move to global helper
     * @param int $positive
     * @param int $total
     * @return int
     */
    private function getConfidenceScore($positive, $total) {
        $z = 1.9208;
        $phat = 1.0 * $positive / $total;
        $n = $phat + $z * $z / (2 * $total) - $z * sqrt(($phat * (1 - $phat) + $z * $z / (4 * $total)) / $total);
        $d  = 1 + $z * $z / $total; 

        return $n / $d;
    }

    /**
     * Hide a tag from trending list
     *
     * @param string $tag
     * @param string $admin_guid
     * @return bool
     */
    public function hide($tag, $admin_guid)
    {
        $cql = "INSERT INTO hidden_hashtags (hashtag, hidden_since, admin_guid) VALUES (?, ?, ?)";
        $values = [
            $tag,
            new Timestamp(time()),
            new Bigint($admin_guid),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Unhide a tag from trending list
     *
     * @param string $tag
     * @return bool
     */
    public function unhide($tag)
    {
        $cql = "DELETE FROM hidden_hashtags WHERE hashtag = ?";
        $values = [
            $tag
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Return hidden hashtags list
     * @param array $opts
     * @return array
     */
    public function getHidden(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 500
        ], $opts);

        $cql = "SELECT hashtag, hidden_since, admin_guid FROM hidden_hashtags";

        $prepared = new Custom();
        $prepared->query($cql);
        $prepared->setOpts([
            'page_size' => (int) $opts['limit'],
        ]);

        $rows = $this->db->request($prepared);
        $response = [];

        foreach ($rows as $row) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * Clear trending hidden table
     * @return bool
     */
    public function clearHidden()
    {
        $cql = "TRUNCATE TABLE hidden_hashtags";

        $prepared = new Custom();
        $prepared->query($cql);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
