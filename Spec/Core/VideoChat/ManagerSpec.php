<?php

namespace Spec\Minds\Core\VideoChat;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\cache\Redis;
use Minds\Core\VideoChat\Manager;
use Minds\Entities\Group;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    /** @var abstractCacher */
    private $cacher;

    function let(Redis $cacher)
    {
        $this->cacher = $cacher;

        $this->beConstructedWith($cacher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_the_room_key(Group $group)
    {
        $group->getGuid()
            ->shouldBeCalled()
            ->willReturn('groupname');

        $this->setEntity($group);
        $this->getRoomKey()->shouldContain('minds-groupnam');
    }

    function it_should_refresh_a_rooms_ttl()
    {
        $key = 'minds-groupnam-asdasd123';
        $this->cacher->get($key)
            ->shouldBeCalled()
            ->willReturn(1);

        $this->cacher->set($key, true, 7200)
            ->shouldBeCalled();

        $this->refreshTTL($key);
    }

    function it_shouldnt_refresh_ttl_if_the_room_does_not_exist()
    {
        $key = 'minds-groupnam-asdasd123';
        $this->cacher->get($key)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->refreshTTL($key);
    }
}
