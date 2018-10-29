<?php

namespace Minds\Core\Hashtags\Suggested;

use Minds\Core\Di\Di;

class Repository
{
    /** @var \PDO $db */
    protected $db;

    public function __construct(\PDO $db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
    }

    /**
     * @param array $opts
     * @return array
     * @throws \Exception
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'limit' => null
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid must be provided');
        }

        $query = "SELECT DISTINCT
                CASE WHEN user_hashtags.hashtag IS NOT NULL THEN user_hashtags.hashtag ELSE entity_hashtags.hashtag END as value,
                CASE WHEN user_hashtags.guid IS NOT NULL THEN true ELSE false END as selected
            FROM suggested
            JOIN entity_hashtags
                ON suggested.guid = entity_hashtags.guid
            FULL OUTER JOIN user_hashtags
                ON (entity_hashtags.hashtag = user_hashtags.hashtag OR user_hashtags.hashtag = null)
            WHERE suggested.lastSynced > ? OR suggested.lastsynced IS NULL AND user_hashtags.guid = ?
            ORDER BY selected DESC, suggested.score DESC";

        $params = [
            date('c', strtotime('24 hours ago')),
            $opts['user_guid'],
        ];

        if ($opts['limit']) {
            $query .= " LIMIT ?";
            $params[] = $opts['limit'];
        }

        $statement = $this->db->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
