<?php

namespace Groups\Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Core\Data\Cassandra\Thrift\Indexes;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class NotificationsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Notifications');
    }

    public function it_should_get_recipients(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('member', Argument::any())->shouldBeCalled()->willReturn([1, 2, 3, 4, 5, 6, 7, 8]);

        $cql->request(Argument::that(function($prepared) {
            $statement = $prepared->build();
            return stripos($statement['string'], 'SELECT * from relationships') !== false && $statement['values'][0] == '50:group:muted:inverted';
        }))->shouldBeCalled()->willReturn([
            [ 'column1' => '6' ],
            [ 'column1' => '7' ],
        ]);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);

        $this->getRecipients([ 'exclude' => [ 1 ] ])->shouldReturn(['2', '3', '4', '5', '8']);
    }

    public function it_should_get_muted_members(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $cql->request(Argument::that(function($prepared) {
            $statement = $prepared->build();
            return stripos($statement['string'], 'SELECT * from relationships') !== false && $statement['values'][0] == '50:group:muted:inverted';
        }))->shouldBeCalled()->willReturn([
            [ 'column1' => '6' ],
            [ 'column1' => '7' ],
        ]);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);

        $this->getMutedMembers()->shouldReturn([ 
            [ 'column1' => '6' ],
            [ 'column1' => '7' ],
        ]);
    }

    public function it_should_check_muted_members_in_batch(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $cql->request(Argument::that(function($prepared) {
            $statement = $prepared->build();
            return stripos($statement['string'], 'SELECT * from relationships') !== false && $statement['values'][0] == '50:group:muted:inverted';
        }))->shouldBeCalled()->willReturn([
            [ 'column1' => '11' ],
            [ 'column1' => '12' ],
            [ 'column1' => '13' ],
        ]);

        $this->isMutedBatch([11, 12, 14])->shouldReturn([11 => true, 12 => true, 14 => false]);
    }

    public function it_should_check_if_its_muted(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql, User $user)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->isMuted($user)->shouldReturn(true);
    }

    public function it_should_mute(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql, User $user)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->mute($user)->shouldReturn(true);
    }

    public function it_should_unmute(GroupEntity $group, Relationships $db, Indexes $indexesDb, Cassandra\Client $cql, User $user)
    {
        $this->beConstructedWith($db, $indexesDb, $cql);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->unmute($user)->shouldReturn(true);
    }
}
