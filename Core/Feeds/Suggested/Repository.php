<?php

namespace Minds\Core\Feeds\Suggested;

use Minds\Core\Di\Di;

class Repository
{
    /** @var \PDO */
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
    }

    /**
     * @param array $opts
     * @return array
     * @throws \Exception
     */
    public function getFeed(array $opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'offset' => 0,
            'limit' => 12,
            'rating' => 1,
            'hashtag' => null,
            'type' => null,
            'all' => false, // if true, it ignores user selected hashtags
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid must be provided');
        }

        if (!$opts['type']) {
            throw new \Exception('type must be provided');
        }

        if ($opts['hashtag']) {
            $query = "SELECT DISTINCT suggested.guid as guid,
                        lastSynced, score
                      FROM suggested
                      JOIN entity_hashtags
                        ON suggested.guid = entity_hashtags.guid
                      WHERE entity_hashtags.hashtag = ?
                        AND type = ?
                        AND rating <= ?
                      ORDER BY lastSynced DESC, score DESC
                      LIMIT ? OFFSET ?";

            $opts = [
                $opts['hashtag'],
                $opts['type'],
                $opts['rating'],
                $opts['limit'],
                $opts['offset']
            ];
        } else {

            // ignore user selected hashtags if all is true
            if ($opts['all']) {
                $query = "SELECT DISTINCT suggested.guid as guid,
                    lastSynced, score
                  FROM suggested
                  WHERE type = ?
                    AND rating <= ?
                  ORDER BY lastSynced DESC, score DESC
                  LIMIT ? OFFSET ?";

                $opts = [
                    $opts['type'],
                    $opts['rating'],
                    $opts['limit'],
                    $opts['offset']
                ];
            } else {
                $query = "SELECT DISTINCT suggested.guid as guid,
                    lastSynced, score
                  FROM user_hashtags
                  INNER JOIN entity_hashtags
                    ON user_hashtags.hashtag = entity_hashtags.hashtag
  	              INNER JOIN suggested
                    ON entity_hashtags.guid = suggested.guid
                  WHERE user_hashtags.guid = ?
                    AND type = ?
                    AND rating <= ?
                  ORDER BY lastSynced DESC, score DESC
                  LIMIT ? OFFSET ?";

                $opts = [
                    $opts['user_guid'],
                    $opts['type'],
                    $opts['rating'],
                    //date('c', strtotime('-48 minutes') / $opts['user_hashtag_count']),
                    //strtotime('-48 hours'),
                    $opts['limit'],
                    $opts['offset']
                ];
            }
        }

        $statement = $this->db->prepare($query);

        $statement->execute($opts);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function add(array $opts = [])
    {
        $opts = array_merge([
            'entity_guid' => null,
            'score' => null,
            'type' => null,
            'rating' => null,
            'lastSynced' => time(),
        ], $opts);

        if (!$opts['entity_guid']) {
            throw new \Exception('entity_guid must be provided');
        }

        if (!$opts['score']) {
            throw new \Exception('score must be provided');
        }

        if (!$opts['type']) {
            throw new \Exception('type must be provided');
        }

        if (!$opts['rating']) {
            throw new \Exception('rating must be provided');
        }

        $query = "UPSERT INTO suggested (guid, rating, type, score, lastSynced) VALUES (?, ?, ?, ?, ?)";
        $values = [
            $opts['entity_guid'],
            $opts['rating'],
            $opts['type'],
            $opts['score'],
            date('c', $opts['lastSynced']),
        ];

        $statement = $this->db->prepare($query);

        return $statement->execute($values);

        //if ($rating > 1) {
        //    $template .= " USING TTL 1200";
        //}
    }

    public function removeAll($type)
    {
        if (!$type) {
            throw new \Exception('type must be provided');
        }
        if ($type === 'all') {
            $statement = $this->db->prepare("TRUNCATE suggested");
            return $statement->execute();
        }

        $selectQuery = "SELECT suggested.guid AS guid
                        FROM suggested
                        JOIN entity_hashtags
                          ON suggested.guid = entity_hashtags.guid
                        WHERE suggested.type = ?";


        $params = [
            $type
        ];

        $statement = $this->db->prepare($selectQuery);

        $statement->execute($params);

        $guids = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $guids = array_map(function ($item) {
            return $item['guid'];
        }, $guids);

        $variables = implode(',', array_fill(0, count($guids), '?'));

        $deleteQuery = "DELETE FROM suggested WHERE guid IN ({$variables})";

        $statement = $this->db->prepare($deleteQuery);

        return $statement->execute($guids);
    }
}
