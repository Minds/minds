<?php

namespace Minds\Core\Feeds\Firehose;

use Minds\Core\Di\Di;
use Minds\Core\Data\Redis\Client as Redis;
use Minds\Core\Config;
use Minds\Entities\User;

class ModerationCache
{
    const MODERATION_CACHE_TTL = 60 * 60; // 1 hour
    const MODERATION_PREFIX = 'moderation_leases';

    /** @var Redis $redis */
    private $redis;

    /** @var Config $config */
    private $config;

    public function __construct(
        Redis $redis = null,
        Config $config = null
    ) {
        $this->config = $config ?: Di::_()->get('Config');
        $this->redis = $redis ?: Di::_()->get('Redis');
    }

    /**
     * Store a lease in a redis set undet the moderation prefix
     *
     * @param string $entityGUID
     * @param User   $user
     * @param int    $ttl
     */
    public function store(string $entityGUID, User $user, int $time = null, int $ttl = ModerationCache::MODERATION_CACHE_TTL)
    {
        $time = $time ?: time();
        try {
            $expire = $time + $ttl;
            $lease = implode(':', [
                (string) $entityGUID,
                $user->getGuid(),
                $expire,
            ]);
            $this->redis->sAdd(ModerationCache::MODERATION_PREFIX, $lease);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Return leases that others have.
     *
     * @param User $user
     *
     * @return array
     */
    public function getKeysLockedByOtherUsers(User $user, int $time = null)
    {
        $time = $time ?: time();
        $locks = [];
        try {
            foreach ($this->redis->sMembers(ModerationCache::MODERATION_PREFIX) as $lease) {
                list($entityGuid, $userGuid, $expire) = explode(':', $lease);
                if ((int) $expire < $time) { //Should have expired, cleanup
                    $this->redis->sRem(ModerationCache::MODERATION_PREFIX, $lease);
                }
                if ($userGuid != $user->getGuid()) {
                    $locks[] = (int) $entityGuid;
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $locks;
    }
}
