<?php

namespace Spec\Minds\Core\VideoChat\Leases;

use Minds\Core\VideoChat\Leases\Manager;
use Minds\Core\VideoChat\Leases\Repository;
use Minds\Core\VideoChat\Leases\VideoChatLease;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;

    function let(Repository $repository)
    {
        $this->beConstructedWith($repository);
        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_add_a_lease()
    {
        $lease = new VideoChatLease();

        $this->repository->add($lease)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($lease)->shouldReturn(true);
    }

    function it_should_return_a_lease()
    {
        $lease = new VideoChatLease();
        $lease->setKey('test')
            ->setSecret('secret');

        $this->repository->get('test')
            ->shouldBeCalled()
            ->willReturn($lease);

        $this->get('test')
            ->shouldReturn($lease);
    }

}
