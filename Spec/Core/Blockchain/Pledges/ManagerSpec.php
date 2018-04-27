<?php

namespace Spec\Minds\Core\Blockchain\Pledges;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Pledges\Repository;
use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Pledges\Manager');
    }

    function it_should_return_the_users_pledges(Repository $repo)
    {
        $this->beConstructedWith($repo);

        $repo->get('hash')
            ->shouldBeCalled()
            ->willReturn((new Pledge)->setAmount(10));

        $user = new User();
        $user->phone_number_hash = 'hash';
        $this->getPledgedAmount($user)->shouldBe("10");
    }
}
