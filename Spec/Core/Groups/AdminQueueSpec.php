<?php

namespace Spec\Minds\Core\Groups;

use Minds\Entities\Activity;
use Minds\Entities\Group;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks;
use Minds\Core\Data\Cassandra;

class AdminQueueSpec extends ObjectBehavior
{
    protected $_client;

    function let(
        Cassandra\Client $client
    )
    {
        $this->beConstructedWith($client);
        $this->_client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Groups\AdminQueue');
    }

    // getAll()

    function it_should_get_all(
        Group $group
    )
    {
        $rows = new Mocks\Cassandra\Rows([], '');

        $group->getGuid()->willReturn(1000);

        $this->_client->request(Argument::that(function ($query) {
            return $query->build()['values'][0] == 'group:adminqueue:1000';
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->getAll($group)
            ->shouldReturn($rows);
    }

    function it_should_throw_during_get_all_if_no_group()
    {
        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringGetAll(null);
    }

    // count()

    function it_should_count(
        Group $group
    )
    {
        $rows = new Mocks\Cassandra\Rows([], '');

        $group->getGuid()->willReturn(1000);

        $this->_client->request(Argument::that(function ($query) {
            return $query->build()['values'][0] == 'group:adminqueue:1000';
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->count($group)
            ->shouldReturn($rows);
    }

    function it_should_throw_during_count_if_no_group()
    {
        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringCount(null);
    }

    // add()

    function it_should_add(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);

        $this->_client->request(Argument::that(function ($query) {
            return $query->build()['values'][0] == 'group:adminqueue:1000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($group, $activity)
            ->shouldReturn(true);
    }

    function it_should_throw_during_add_if_no_group(
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringAdd(null, $activity);
    }

    function it_should_throw_during_add_if_no_activity(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn('');

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringAdd($group, $activity);
    }

    function it_should_throw_during_add_if_activity_doesnt_belong_to_group(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1001);

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringAdd($group, $activity);
    }

    // add()

    function it_should_delete(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);

        $this->_client->request(Argument::that(function ($query) {
            return $query->build()['values'][0] == 'group:adminqueue:1000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($group, $activity)
            ->shouldReturn(true);
    }

    function it_should_throw_during_delete_if_no_group(
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringDelete(null, $activity);

    }

    function it_should_throw_during_delete_if_no_activity(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn('');

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringDelete(null, $activity);
    }

    function it_should_throw_during_delete_if_activity_doesnt_belong_to_group(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1001);

        $this->_client->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringDelete($group, $activity);
    }
}
