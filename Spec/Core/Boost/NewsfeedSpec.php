<?php

namespace Spec\Minds\Core\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Minds\Entities\User;
use Minds\Entities\Boost\Network;
use Minds\Core\Data\Call;
use Minds\Core\Data\MongoDB\Client;
use Minds\Core\Data\Interfaces\ClientInterface;

class NewsfeedSpec extends ObjectBehavior
{
    public function let(Client $mongo, Call $db, User $user)
    {
        $mongo->insert(Argument::type('string'), Argument::type('array'))
          ->willReturn("boost_id");

        //$db->getRow(Argument::type(''))->will

        $this->beConstructedWith([], $mongo, $db);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Newsfeed');
    }

    public function it_can_boost_a_post(Network $boost, User $user, Client $mongo, Call $db)
    {
        $boost->getGuid()->willReturn('foo');
        $boost->getImpressions()->willReturn(10);
        $boost->getOwner()->willReturn($user);

        $boost->getRating()->willReturn(1);
        $boost->getQuality()->willReturn(75);
        $boost->getImpressions()->willReturn(100);
        $boost->getPriorityRate()->willReturn(0);
        $boost->getCategories()->willReturn(['art', 'music']);

        $this->boost($boost)->shouldBeString();
    }
}
