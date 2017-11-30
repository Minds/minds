<?php

namespace Spec\Minds\Core\Votes;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Entities\Activity;
use Minds\Entities\User;
use Minds\Core\Votes\Vote;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountersSpec extends ObjectBehavior
{
    protected $cql;
    protected $cacher;

    public function let(
        Client $cql,
        abstractCacher $cacher
    )
    {
        $this->cql = $cql;
        $this->cacher = $cacher;

        $this->beConstructedWith($cql, $cacher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Votes\Counters');
    }

    function it_should_update_counters(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:count')->willReturn(0);
        $entity->get('remind_object')->willReturn(null);

        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:count', 1];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->update($vote)
            ->shouldReturn(true);
    }

    function it_should_update_counters_with_existing_count(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:count')->willReturn(129);
        $entity->get('remind_object')->willReturn(null);

        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:count', 130];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->update($vote)
            ->shouldReturn(true);
    }

    function it_should_get_count(
        Activity $activity
    )
    {
        $activity->get('thumbs:up:count')
            ->shouldBeCalled()
            ->willReturn(3);

        $this->get($activity, 'up')
            ->shouldReturn(3);
    }

    function it_should_throw_during_count_if_invalid_direction(
        Activity $activity
    )
    {
        $activity->get('thumbs:upside_down:count')
            ->shouldNotBeCalled();

        $this->shouldThrow(new \Exception('Invalid direction'))
            ->duringGet($activity, 'upside_down');
    }

}
