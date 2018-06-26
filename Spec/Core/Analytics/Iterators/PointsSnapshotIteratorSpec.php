<?php

namespace Spec\Minds\Core\Analytics\Iterators;

use Minds\Core\Analytics\Iterators\PointsSnapshotIterator;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class PointsSnapshotIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $db;
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(Client $db, EntitiesBuilder $entitiesBuilder)
    {
        $this->beConstructedWith($db, $entitiesBuilder);
        $this->db = $db;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PointsSnapshotIterator::class);
    }

    function it_should_get_the_user_list(User $user1, User $user2)
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === 'SELECT * from entities_by_time where key=\'points:snapshot\' and column1>?'
                && $built['values'] === [''];
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([
                ['column1' => '1234', 'value' => 10],
                ['column1' => '5678', 'value' => 37],
            ], ''));

        $user1->get('guid')
            ->shouldBeCalled()
            ->willReturn('1234');
        $user1->get('time_created')
            ->shouldBeCalled()
            ->willReturn(strtotime('-10 day'));
        $user1->set('points_snapshot', 10)
            ->shouldBeCalled();

        $user2->get('guid')
            ->shouldBeCalled()
            ->willReturn('5678');
        $user2->get('time_created')
            ->shouldBeCalled()
            ->willReturn(strtotime('-10 day'));
        $user2->set('points_snapshot', 37)
            ->shouldBeCalled();

        $this->entitiesBuilder->get(['guids' => ['1234', '5678']])
            ->shouldBeCalled()
            ->willReturn([$user1, $user2]);


        $this->next();
        $this->current()->shouldReturn($user1);
        $this->next();
        $this->current()->shouldReturn($user2);

    }
}
