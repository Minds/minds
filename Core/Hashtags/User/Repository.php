<?php

namespace Minds\Core\Hashtags\User;

use Cassandra\Varint;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Hashtags\HashtagEntity;

class Repository
{
    /** @var Client */
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return bool|Response
     * @throws \Exception
     */
    public function getAll($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid must be provided');
        }

        $cql = "SELECT hashtag FROM user_hashtags WHERE user_guid = ?";
        $params = [
            new Varint($opts['user_guid'])
        ];

        $prepared = new Custom();
        $prepared->query($cql, $params);

        try {
            $rows = $this->db->request($prepared);

            if (!$rows) {
                return false;
            }

            $response = new Response();

            foreach ($rows as $row) {
                $response[] = (new HashtagEntity())
                    ->setGuid($opts['user_guid'])
                    ->setHashtag($row['hashtag']);
            }

            return $response;
        } catch (\Exception $e) {
            error_log(static::class . '::getAll() CQL Exception ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function add(array $hashtags)
    {
        $cql = "INSERT INTO user_hashtags (user_guid, hashtag) VALUES (?, ?)";

        foreach ($hashtags as $hashtag) {
            try {
                $params = [
                    new Varint($hashtag->getGuid()),
                    (string) $hashtag->getHashtag()
                ];

                $prepared = new Custom();
                $prepared->query($cql, $params);

                $this->db->request($prepared, true);
            } catch (\Exception $e) {
                error_log(static::class . '::add() CQL Exception ' . $e->getMessage());
                return false;
            }
        }

        return true;
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function remove(array $hashtags)
    {
        $userGuid = $hashtags[0]->getGuid();

        $hashtagValues = array_map(function ($hashtag) {
            return $hashtag->getHashtag();
        }, $hashtags);
        $hashtagCollection = \Cassandra\Type::collection(\Cassandra\Type::text())->create(...$hashtagValues);

        $cql = "DELETE FROM user_hashtags WHERE user_guid = ? AND hashtag IN ?";
        $params = [
            new Varint($userGuid),
            $hashtagCollection
        ];

        $prepared = new Custom();
        $prepared->query($cql, $params);

        try {
            $this->db->request($prepared, true);
        } catch (\Exception $e) {
            error_log(static::class . '::remove() CQL Exception ' . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function update(array $hashtags)
    {
        return $this->add($hashtags);
    }
}
