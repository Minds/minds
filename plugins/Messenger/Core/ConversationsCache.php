<?php
/**
 * Minds messenger conversations cache
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra;
use Minds\Core\Session;
use Minds\Entities\User;
use Minds\Plugin\Messenger;

class ConversationsCache
{
    private $redis;
    private $user;
    private $user_guid;

    public function __construct($redis = null, $config = null)
    {
        $this->redis = $redis ?: new \Redis();
        $this->config = $config ?: Di::_()->get('Config');
        $this->setUser(Session::getLoggedinUser());
    }

    public function setUser($user)
    {
        if ($user instanceof User) {
            $this->user = $user;
            $this->user_guid = $user->guid;
        } elseif (is_string($user)) {
            $this->user_guid = $user;
        }

        return $this;
    }

    public function getGuids($limit = 12, $offset = 0)
    {
        $return = [];

        try {
            $config = $this->config->get('redis');
            $this->redis->connect($config['pubsub'] ?: $config['master'] ?: '127.0.0.1');
            $return = $this->redis->smembers("object:gathering:conversations:{$this->user_guid}");
        } catch (\Exception $e) {
        }

        return $return;
    }


    public function saveList($conversations)
    {
        try {
            $config = $this->config->get('redis');
            $this->redis->connect($config['pubsub'] ?: $config['master'] ?: '127.0.0.1');
            $guids = array_map(function ($c) {
                return $c->getGuid();
            }, $conversations);
            array_unshift($guids, "object:gathering:conversations:{$this->user_guid}");
            call_user_func_array([$this->redis, 'sadd'], $guids);
        } catch (\Exception $e) {
        }
    }
}
