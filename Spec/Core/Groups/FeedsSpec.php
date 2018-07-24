<?php

namespace Spec\Minds\Core\Groups;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Groups\AdminQueue;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Entities\Group;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Spec\Minds\Mocks;

class FeedsSpec extends ObjectBehavior
{
    protected $_adminQueue;
    protected $_entities;
    protected $_entitiesFactory;
    protected $_entitiesBuilder;

    function let(
        AdminQueue $adminQueue,
        Mocks\Minds\Core\Entities $entities,
        Mocks\Minds\Core\Entities\Factory $entitiesFactory,
        Core\EntitiesBuilder $entitiesBuilder
    )
    {
        // AdminQueue

        Di::_()->bind('Groups\AdminQueue', function () use ($adminQueue) {
            return $adminQueue->getWrappedObject();
        });

        $this->_adminQueue = $adminQueue;

        // Entities

        Di::_()->bind('Entities', function () use ($entities) {
            return $entities->getWrappedObject();
        });

        $this->_entities = $entities;

        // Entities Factory

        Di::_()->bind('Entities\Factory', function () use ($entitiesFactory) {
            return $entitiesFactory->getWrappedObject();
        });

        $this->_entitiesFactory = $entitiesFactory;

        $this->_entitiesBuilder = $entitiesBuilder;

        $this->beConstructedWith($entitiesBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Groups\Feeds');
    }

    // setGroup()

    function it_should_set_group(Group $group)
    {
        $this
            ->setGroup($group)
            ->shouldReturn($this);
    }

    // getAll()

    function it_should_get_all(
        Group $group,
        Activity $activity_1,
        Activity $activity_2
    )
    {
        $activity_1->get('guid')->willReturn(5000);
        $activity_2->get('guid')->willReturn(5001);

        $rows = new Mocks\Cassandra\Rows([
            [ 'value' => 5000 ],
            [ 'value' => 5001 ],
        ], '');

        $this->_adminQueue->getAll($group, [])
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->_entities->get([ 'guids' => [ 5000, 5001 ]])
            ->shouldBeCalled()
            ->willReturn([
                $activity_1,
                $activity_2
            ]);

        $return = $this
            ->setGroup($group)
            ->getAll();

        $return->shouldHaveKeys(['data', 'next']);
        $return['data']->shouldBeAnArrayOf(2, Activity::class);
        $return['next']->shouldReturn('');
    }


    function it_should_return_an_empty_array_during_get_all(
        Group $group
    )
    {
        $rows = new Mocks\Cassandra\Rows([], '');

        $this->_adminQueue->getAll($group, [])
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->_entities->get(Argument::any())
            ->shouldNotBeCalled();

        $return = $this
            ->setGroup($group)
            ->getAll();

        $return->shouldHaveKeys(['data', 'next']);
        $return['data']->shouldBeAnArrayOf(0, Activity::class);
        $return['next']->shouldReturn('');
    }

    function it_should_throw_during_get_all_if_no_group()
    {
        $this->_adminQueue->getAll(Argument::any())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringGetAll();
    }

    // count()

    function it_should_count(
        Group $group
    )
    {
        $this->_adminQueue->count($group)
            ->shouldBeCalled()
            ->willReturn([
                [ 'count' => new Mocks\Cassandra\Value(2) ]
            ]);

        $this
            ->setGroup($group)
            ->count()
            ->shouldReturn(2);
    }

    function it_should_count_zero_if_no_rows(
        Group $group
    )
    {
        $this->_adminQueue->count($group)
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setGroup($group)
            ->count()
            ->shouldReturn(0);
    }

    function it_should_throw_during_count_if_no_group()
    {
        $this->_adminQueue->count(Argument::any())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringCount();
    }

    // queue()

    function it_should_queue(
        Group $group,
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn(5000);

        $this->_adminQueue->add($group, $activity)
            ->shouldBeCalled()
            ->willReturn(true);


        $this
            ->setGroup($group)
            ->queue($activity, [ 'notification' => false ])
            ->shouldReturn(true);
    }

    function it_should_throw_during_queue_if_no_group(
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn(5000);

        $this->_adminQueue->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringQueue($activity, [ 'notification' => false ]);
    }

    function it_should_throw_during_queue_if_no_activity(
        Group $group,
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn('');

        $this->_adminQueue->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setGroup($group)
            ->shouldThrow(\Exception::class)
            ->duringQueue($activity, [ 'notification' => false ]);
    }

    // approve()

    function it_should_approve(
        Group $group,
        Activity $activity,
        Entities\Image $attachment
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);
        $activity->get('owner_guid')->willReturn(10000);
        $activity->get('entity_guid')->willReturn(8888);

        $activity->setPending(false)
            ->shouldBeCalled();

        $activity->save(true)
            ->shouldBeCalled();

        $this->_adminQueue->delete($group, $activity)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->_entitiesBuilder->single(8888)
            ->shouldBeCalled()
            ->willReturn($attachment);

        $attachment->get('subtype')
            ->shouldBeCalled()
            ->willReturn('image');

        $attachment->getWireThreshold()
            ->shouldBeCalled()
            ->willReturn(false);

        $attachment->set('access_id', 2)
            ->shouldBeCalled()
            ->willReturn(null);

        $attachment->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setGroup($group)
            ->approve($activity, [ 'notification' => false ])
            ->shouldReturn(true);
    }

    function it_should_throw_during_approve_if_no_group(
        Activity $activity
    )
    {
        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringApprove($activity, [ 'notification' => false ]);
    }

    function it_should_throw_during_approve_if_no_activity(
        Group $group,
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn('');

        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setGroup($group)
            ->shouldThrow(\Exception::class)
            ->duringApprove($activity, [ 'notification' => false ]);
    }

    function it_should_throw_during_approve_if_activity_doesnt_belong_to_group(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);

        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1001);

        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setGroup($group)
            ->shouldThrow(\Exception::class)
            ->duringApprove($activity, [ 'notification' => false ]);
    }

    // reject()

    function it_should_reject(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);
        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1000);

        $this->_adminQueue->delete($group, $activity)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setGroup($group)
            ->reject($activity, [ 'notification' => false ])
            ->shouldReturn(true);
    }

    function it_should_throw_during_reject_if_no_group(
        Activity $activity
    )
    {
        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringReject($activity, [ 'notification' => false ]);
    }

    function it_should_throw_during_reject_if_no_activity(
        Group $group,
        Activity $activity
    )
    {
        $activity->get('guid')->willReturn('');

        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setGroup($group)
            ->shouldThrow(\Exception::class)
            ->duringReject($activity, [ 'notification' => false ]);
    }

    function it_should_throw_during_reject_if_activity_doesnt_belong_to_group(
        Group $group,
        Activity $activity
    )
    {
        $group->getGuid()->willReturn(1000);

        $activity->get('guid')->willReturn(5000);
        $activity->get('container_guid')->willReturn(1001);

        $this->_adminQueue->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setGroup($group)
            ->shouldThrow(\Exception::class)
            ->duringReject($activity, [ 'notification' => false ]);
    }

    // approveAll()

    function it_should_approve_all(
        Group $group,
        Activity $activity_1,
        Activity $activity_2,
        Entities\Video $attachment_1
    )
    {
        $group->getGuid()->willReturn(1000);

        $activity_1->get('guid')->willReturn(5001);
        $activity_1->get('container_guid')->willReturn(1000);
        $activity_1->get('owner_guid')->willReturn(10000);
        $activity_1->get('entity_guid')->willReturn(8888);

        $activity_2->get('guid')->willReturn(5002);
        $activity_2->get('container_guid')->willReturn(1000);
        $activity_2->get('owner_guid')->willReturn(10000);
        $activity_2->get('entity_guid')->willReturn(null);

        $this->_entitiesBuilder->single(8888)
            ->shouldBeCalled()
            ->willReturn($attachment_1);

        $attachment_1->get('subtype')
            ->shouldBeCalled()
            ->willReturn('video');

        $attachment_1->getWireThreshold()
            ->shouldBeCalled()
            ->willReturn(false);

        $attachment_1->set('access_id', 2)
            ->shouldBeCalled()
            ->willReturn(null);

        $attachment_1->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->_adminQueue->getAll($group)
            ->shouldBeCalled()
            ->willReturn([
                [ 'value' => 5001 ],
                [ 'value' => 5002 ],
            ]);

        $this->_entitiesFactory->build(5001)
            ->shouldBeCalled()
            ->willReturn($activity_1);

        $this->_entitiesFactory->build(5002)
            ->shouldBeCalled()
            ->willReturn($activity_2);

        // approve()

        $activity_1->setPending(false)
            ->shouldBeCalled();

        $activity_1->save(true)
            ->shouldBeCalled();

        $activity_2->setPending(false)
            ->shouldBeCalled();

        $activity_2->save(true)
            ->shouldBeCalled();

        $this->_adminQueue->delete($group, Argument::type(Activity::class))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        //

        $this
            ->setGroup($group)
            ->approveAll()
            ->shouldReturn([
                5001 => true,
                5002 => true
            ]);
    }

    function it_should_throw_during_approve_all_if_no_group()
    {
        $this->_adminQueue->getAll(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(\Exception::class)
            ->duringApproveAll();
    }

    //

    function getMatchers()
    {
        $matchers = [];

        $matchers['haveKeys'] = function($subject, array $keys) {
            $valid = true;

            foreach ($keys as $key) {
                $valid = $valid && array_key_exists($key, $subject);
            }

            return $valid;
        };

        $matchers['beAnArrayOf'] = function ($subject, $count, $class) {
            if (!is_array($subject) || ($count !== null && count($subject) !== $count)) {
                throw new FailureException("Subject should be an array of $count elements");
            }

            $validTypes = true;

            foreach ($subject as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be an array of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
