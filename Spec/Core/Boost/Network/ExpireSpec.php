<?php

namespace Spec\Minds\Core\Boost\Network;

use Minds\Core\Data\MongoDB;
use Minds\Entities\Boost\Network;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpireSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Network\Expire');
    }

    function it_should_expire_a_boost(MongoDB\Client $mongo, Network $boost)
    {
        $mongo->remove(Argument::containingString('boost'), Argument::any())->shouldBeCalled();

        $this->beConstructedWith($mongo);
        $boost->setState('approved');
        $boost->getId()->willReturn('1');

        $owner = new \stdClass();
        $owner->guid = 123;
        $boost->getOwner()->willReturn($owner);

        $entity = new \stdClass();
        $entity->title = 'title';
        $boost->getEntity()->willReturn($entity);

        $boost->getState()->willReturn('approved');
        $boost->getBid()->willReturn('1000');
        $boost->getImpressions()->willReturn('1000');

        $boost->setState(Argument::containingString('completed'))->willReturn($boost);
        $boost->save()->shouldBeCalled()->willReturn();
        $this->setBoost($boost);

        $this->expire();
    }
}
