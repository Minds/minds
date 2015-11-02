<?php

namespace Spec\Minds\Core\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Minds\Entities\User;
use Minds\Entities\Activity;
use Minds\Core\Data\MongoDB\Client;
use Minds\Core\Data\Interfaces\ClientInterface;

class NewsfeedSpec extends ObjectBehavior
{
    public function let(Client $db, User $user)
    {
        $_SESSION['user'] = $user;
        $_SESSION['username'] = "test";
        $_SESSION['guid'] = "test";
        $db->insert(Argument::type('string'), Argument::type('array'))
      ->willReturn("boost_id");
        $this->beConstructedWith(array(), $db);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Newsfeed');
    }

    public function it_can_boost_a_post(Activity $activity)
    {
        $this->boost($activity, 100)->shouldReturn("boost_id");
    }
}
