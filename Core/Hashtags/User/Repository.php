<?php

namespace Minds\Core\Hashtags\User;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Core\Hashtags\HashtagEntity;

class Repository
{
    /** @var \PDO */
    protected $db;

    /** @var abstractCacher */
    protected $cacher;

    public function __construct($db = null, $cacher = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
        $this->cacher = $cacher ?: Di::_()->get('Cache');
    }

    /**
     * Return all hashtags
     */
    public function getAll($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid must be provided');
        }

        $query = "SELECT hashtag FROM user_hashtags WHERE guid = ?";
        $params = [$opts['user_guid']];

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
        $query = "UPSERT INTO user_hashtags(guid, hashtag) VALUES (?, ?)";

        foreach ($hashtags as $hashtag) {
            try {
                $statement = $this->db->prepare($query);

                $this->cacher->destroy("user-selected-hashtags:{$hashtag->getGuid()}");

                return $statement->execute([$hashtag->getGuid(), $hashtag->getHashtag()]);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }
        return false;
    }

    /**
     * @param $user_guid
     * @param array $hashtags
     * @return bool
     */
    public function remove($user_guid, array $hashtags)
    {
        $variables = implode(',', array_fill(0, count($hashtags), '?'));

        $query = "DELETE FROM user_hashtags WHERE guid = ? AND hashtag IN ({$variables})";

        $statement = $this->db->prepare($query);

        $this->cacher->destroy("user-selected-hashtags:{$user_guid}");

        return $statement->execute(array_merge([$user_guid], $hashtags));
    }

    public function update()
    {

    }

}
