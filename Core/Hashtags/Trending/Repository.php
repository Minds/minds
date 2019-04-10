<?php

namespace Minds\Core\Hashtags\Trending;

use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared\Search as Prepared;
use Minds\Common\Repository\Response;

/**
 * Hashtags Trending Repository
 */
class Repository
{
    /** @var \PDO $db */
    protected $db;

    /** @var ElasticSearch $es */
    protected $es;

    public function __construct(\PDO $db = null, $es = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
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
     * Return a confifdence score
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
     * Get trending hashtags
     *
     * @param array $opts
     * @return array
     */
    public function getTrending(array $opts = [])
    {
        $opts = array_merge([
            'from_date' => date('c', strtotime('24 hours ago')),
            'limit' => 20
        ], $opts);

        $query = "SELECT entity_hashtags.hashtag, COUNT(*) as hashCount
                    FROM entity_hashtags
                    JOIN suggested ON suggested.guid = entity_hashtags.guid
                    LEFT JOIN hidden_hashtags ON hidden_hashtags.hashtag = entity_hashtags.hashtag
                    WHERE suggested.lastsynced >= ? AND hidden_hashtags.hashtag IS NULL
                    GROUP BY entity_hashtags.hashtag
                    ORDER BY hashCount Desc
                    LIMIT ?";
       
        $statement = $this->db->prepare($query);

        $statement->execute([$opts['from_date'], $opts['limit']]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Hide a tag from trending list
     *
     * @param string $tag
     * @param string $admin_guid
     * @return void
     */
    public function hide($tag, $admin_guid)
    {
        $query = "INSERT INTO hidden_hashtags(hashtag, hidden_since, admin_guid) VALUES (?, ?, ?)";

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute([$tag, date('c'), $admin_guid]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return false;
    }

    /**
     * Unhide a tag from trending list
     *
     * @param string $tag
     * @return void
     */
    public function unhide($tag)
    {
        $query = "DELETE FROM hidden_hashtags WHERE hashtag = ?";

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute([$tag]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return false;
    }

    /**
     * Return hidden hashtags list
     */
    public function getHidden($opts = [])
    {
        $opts = array_merge([
            'limit' => 500
        ], $opts);

        $query = "SELECT hashtag, admin_guid, hidden_since FROM hidden_hashtags LIMIT ?";
        $params = [$opts['limit']];

        $statement = $this->db->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Clear trending hidden table
     *
     * @return void
     */
    public function clearHidden()
    {
        $query = "TRUNCATE TABLE hidden_hashtags";

        try {
            $statement = $this->db->prepare($query);

            return $statement->execute();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return false;
    }
}
