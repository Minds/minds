<?php

namespace Minds\Core\Hashtags\Entity;

use Minds\Core\Di\Di;
use Minds\Core\Hashtags\HashtagEntity;

class Repository
{
    /** @var \PDO */
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
    }

    /**
     * Return all hashtags
     */
    public function getAll($opts = [])
    {
        $opts = array_merge([
            'entity_guid' => null
        ], $opts);

        if (!$opts['entity_guid']) {
            throw new \Exception('entity_guid must be provided');
        }

        $query = "SELECT * FROM entity_hashtags WHERE guid=?";
        $params = [$opts['entity_guid']];

        $statement = $this->db->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function add($hashtags)
    {
        $query = "UPSERT INTO entity_hashtags (guid, hashtag) VALUES (?, ?)";
        foreach ($hashtags as $hashtag) {
            try {
                $statement = $this->db->prepare($query);

                if (!$hashtag->getHashtag()) {
                    continue;
                }

                return $statement->execute([$hashtag->getGuid(), $hashtag->getHashtag()]);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }
        return false;
    }

}
