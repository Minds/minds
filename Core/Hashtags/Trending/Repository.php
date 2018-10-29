<?php

namespace Minds\Core\Hashtags\Trending;

use Minds\Core\Di\Di;

/**
 * Hashtags Trending Repository
 */
class Repository
{
    /** @var \PDO $db */
    protected $db;

    public function __construct(\PDO $db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
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
