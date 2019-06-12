<?php

namespace Spec\Minds\Core\Feeds\Firehose;

use Minds\Core\Feeds\Firehose\ModerationCache;
use PhpSpec\ObjectBehavior;
use Minds\Core\Data\Redis\Client as Redis;
use Minds\Core\Config;
use Minds\Entities\User;

class ModerationCacheSpec extends ObjectBehavior
{
    protected $redis;
    protected $config;

    public function let(Redis $redis, Config $config)
    {
        $this->beConstructedWith($redis, $config);
        $this->redis = $redis;
        $this->config = $config;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ModerationCache::class);
    }

    public function it_should_store_a_key()
    {
        $time = time();
        $expire = $time + ModerationCache::MODERATION_CACHE_TTL;
        $user = (new User())
            ->set('guid', 456);
        $this->redis->sAdd(ModerationCache::MODERATION_PREFIX, "123:456:{$expire}")->shouldBeCalled();
        $this->store('123', $user, $time);
    }

    public function it_should_get_keys_locked_by_others()
    {
        $user = (new User())
            ->set('guid', 456);
        $this->redis->sMembers(ModerationCache::MODERATION_PREFIX)
            ->shouldBeCalled()
            ->willReturn(['123:456:0', '789:012:0']);
        $this->redis->sRem(ModerationCache::MODERATION_PREFIX, '123:456:0')->shouldBeCalled();
        $this->redis->sRem(ModerationCache::MODERATION_PREFIX, '789:012:0')->shouldBeCalled();
        $this->getKeysLockedByOtherUsers($user)->shouldEqual([789]);
    }
}
