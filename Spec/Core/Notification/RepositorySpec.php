<?php

namespace Spec\Minds\Core\Notification;

use Minds\Core\Notification\Notification;
use Minds\Core\Notification\Repository;
use PhpSpec\ObjectBehavior;

class RepositorySpec extends ObjectBehavior
{
    /** @var \PDO */
    private $sql;

    function let(\PDO $sql)
    {
        $this->sql = $sql;

        $this->beConstructedWith($sql);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_a_single_notification(\PDOStatement $statement)
    {
        $this->sql->prepare('SELECT * FROM notifications WHERE uuid = ?')
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(['1234'])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                [
                    'uuid' => '1234',
                    'from_guid' => '456',
                    'entity_guid' => '789',
                    'to_guid' => '987',
                    'created_timestamp' => '5 December 2018',
                    'read_timestamp' => 1234,
                    'notification_type' => 'type',
                    'data' => json_encode(['test' => 'data']),
                ]
            ]);

        $notification = $this->get('1234');

        $notification->shouldBeAnInstanceOf(Notification::class);
        $notification->getUUID()->shouldBe('1234');
        $notification->getFromGuid()->shouldReturn('456');
        $notification->getEntityGuid()->shouldReturn('789');
        $notification->getToGuid()->shouldReturn('987');
        $notification->getCreatedTimestamp()->shouldReturn(1543968000);
        $notification->getReadTimestamp()->shouldReturn(1234);
        $notification->getType()->shouldReturn('type');
        $notification->getData()->shouldReturn(['test' => 'data']);
    }
}
