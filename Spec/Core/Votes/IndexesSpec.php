<?php

namespace Spec\Minds\Core\Votes;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Entities\Activity;
use Minds\Entities\User;
use Minds\Core\Votes\Vote;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexesSpec extends ObjectBehavior
{
    protected $cql;

    function let(
        Client $cql
    )
    {
        $this->cql = $cql;

        $this->beConstructedWith($cql);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Votes\Indexes');
    }

    function it_should_insert(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ "1000" ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'INSERT INTO entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(3)
            ->willReturn(true);

        $this->insert($vote)
            ->shouldReturn(true);
    }

    function it_should_store_appending_actor(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([ "50" ]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ "50", "1000" ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'INSERT INTO entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(3)
            ->willReturn(true);

        $this->insert($vote)
            ->shouldReturn(true);
    }

    function it_should_store_with_an_entity_guid(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(6000);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ "1000" ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'INSERT INTO entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(4)
            ->willReturn(true);

        $this->insert($vote)
            ->shouldReturn(true);
    }

    function it_should_store_with_custom_data(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn([ 'guid' => 7000 ]);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ "1000" ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'INSERT INTO entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(4)
            ->willReturn(true);

        $this->insert($vote)
            ->shouldReturn(true);
    }

    function it_should_remove(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'DELETE FROM entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(3)
            ->willReturn(true);

        $this->remove($vote)
            ->shouldReturn(true);
    }

    function it_should_remove_diffing_actor(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([ "999", 1000 ]);

        $user->get('guid')->willReturn("1000");
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ "999" ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'DELETE FROM entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(3)
            ->willReturn(true);

        $this->remove($vote)
            ->shouldReturn(true);
    }

    function it_should_remove_with_an_entity_guid(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(6000);
        $entity->get('custom_data')->willReturn(null);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'DELETE FROM entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(4)
            ->willReturn(true);

        $this->remove($vote)
            ->shouldReturn(true);
    }

    function it_should_remove_with_custom_data(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('guid')->willReturn(5000);
        $entity->get('type')->willReturn('activity');
        $entity->get('entity_guid')->willReturn(null);
        $entity->get('custom_data')->willReturn([ 'guid' => 7000 ]);
        $entity->get('thumbs:up:user_guids')->willReturn([]);

        $user->get('guid')->willReturn(1000);
        
        $vote->getEntity()->willReturn($entity);
        $vote->getDirection()->willReturn('up');
        $vote->getActor()->willReturn($user);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return $query['values'] == ['5000', 'thumbs:up:user_guids', json_encode([ ])];
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();
            return stripos($query['string'], 'DELETE FROM entities_by_time') === 0;
        }))
            ->shouldBeCalledTimes(4)
            ->willReturn(true);

        $this->remove($vote)
            ->shouldReturn(true);
    }

    function it_should_return_if_exists(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('thumbs:up:user_guids')->willReturn([ 50 ]);
        $user->get('guid')->willReturn(50);

        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->exists($vote)->shouldReturn(true);
    }

    function it_should_not_return_if_exists(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $entity->get('thumbs:up:user_guids')->willReturn([ 50 ]);
        $user->get('guid')->willReturn(70);

        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->exists($vote)->shouldReturn(false);
    }

}
