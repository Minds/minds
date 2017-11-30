<?php

namespace Spec\Minds\Core\Votes;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Core\Votes\Counters;
use Minds\Core\Votes\Indexes;
use Minds\Core\Votes\Vote;
use Minds\Entities\Activity;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    protected $acl;
    protected $counters;
    protected $indexes;

    function let(
        ACL $acl,
        Counters $counters,
        Indexes $indexes
    )
    {
        $this->acl = $acl;
        $this->counters = $counters;
        $this->indexes = $indexes;
        $this->beConstructedWith($counters, $indexes, $acl);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Votes\Manager');
    }

    function it_should_cast(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->acl->interact($entity, $user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->counters->update($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cast($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_throw_during_insert_if_cannot_interact(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->acl->interact($entity, $user)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->counters->update($vote)
            ->shouldNotBeCalled();

        $this->indexes->insert($vote)
            ->shouldNotBeCalled();

        $this->shouldThrow(new \Exception('Actor cannot interact with entity'))
            ->duringCast($vote, [ 'events' => false ]);
    }

    function it_should_cancel(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->counters->update($vote, -1)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->remove($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cancel($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_be_true_if_has(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->has($vote)
            ->shouldReturn(true);
    }

    function it_should_be_false_if_not_has(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->has($vote)
            ->shouldReturn(false);
    }

    function it_should_toggle_on(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->acl->interact($entity, $user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->counters->update($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->toggle($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_toggle_off(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->counters->update($vote, -1)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->remove($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->toggle($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }
}
