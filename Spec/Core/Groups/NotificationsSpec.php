<?php

namespace Spec\Minds\Core\Groups;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Core\Data\Cassandra\Thrift\Indexes;
use Minds\Entities\Group as GroupEntity;
use Minds\Core\Notification\Manager as NotificationManager;
use Minds\Core\Notification\Batches\Manager as NotificationBatches;

class NotificationsSpec extends ObjectBehavior
{

    protected $db;
    protected $indexesDb;
    protected $cql;
    protected $notifications;
    protected $notificationBatches;

    function let(
        Relationships $db,
        Indexes $indexesDb,
        Cassandra\Client $cql,
        NotificationManager $notifications,
        NotificationBatches $notificationBatches
    )
    {
        $this->db = $db;
        $this->indexesDb = $indexesDb;
        $this->cql = $cql;
        $this->notifications = $notifications;
        $this->notificationBatches = $notificationBatches;

        $this->beConstructedWith($db, $indexesDb, $cql, $notifications, $notificationBatches);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Groups\Notifications');
    }

    public function it_should_get_recipients(
        GroupEntity $group
    )
    {
        $this->db->setGuid(50)->shouldBeCalled();
        $this->db->get('member', Argument::any())->shouldBeCalled()->willReturn([1, 2, 3, 4, 5, 6, 7, 8]);

        $this->cql->request(Argument::that(function($prepared) {
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

    public function it_should_get_muted_members(
        GroupEntity $group
    )
    {
        $this->cql->request(Argument::that(function($prepared) {
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

    public function it_should_check_muted_members_in_batch(
        GroupEntity $group
    )
    {
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $this->cql->request(
                Argument::that(function($prepared) {
                    $statement = $prepared->build();
                    return stripos($statement['string'], 'SELECT * from relationships') !== false 
                        && $statement['values'][0] == '50:group:muted:inverted';
                }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'column1' => '11' ],
                [ 'column1' => '12' ],
                [ 'column1' => '13' ],
            ]);

        $this->isMutedBatch([11, 12, 14])->shouldReturn([11 => true, 12 => true, 14 => false]);
    }

    public function it_should_check_if_its_muted(
        GroupEntity $group,
        User $user
    )
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $this->notificationBatches
            ->setUser(1)
            ->shouldBeCalled();

        $this->notificationBatches
            ->setBatchId(50)
            ->shouldBeCalled();

        $this->notificationBatches->isSubscribed()
            ->willReturn(false);
    
        $this->isMuted($user)->shouldReturn(true);
    }

    public function it_should_mute(
        GroupEntity $group,
        User $user
    )
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $this->notificationBatches
            ->setUser(1)
            ->shouldBeCalled();

        $this->notificationBatches
            ->setBatchId(50)
            ->shouldBeCalled();

        $this->notificationBatches->unSubscribe()
            ->willReturn(true);

        $this->mute($user)->shouldReturn(true);
    }

    public function it_should_unmute(
        GroupEntity $group,
        User $user
    )
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $this->setGroup($group);

        $this->notificationBatches
            ->setUser(1)
            ->shouldBeCalled();
        $this->notificationBatches
            ->setBatchId(50)
            ->shouldBeCalled();

        $this->notificationBatches->subscribe()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->unmute($user)->shouldReturn(true);
    }
}
