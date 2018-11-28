<?php

namespace Spec\Minds\Core\Notification\Batches;

use Minds\Core\Notification\Batches\Manager;
use Minds\Core\Notification\Batches\Repository;
use Minds\Core\Notification\Batches\BatchSubscription;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    protected $db;

    function let(\PDO $db)
    {
        $this->db = $db;
        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_single_subscription(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(123)
            ->setBatchId('phpspec');

        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
                'phpspec', // batch_id
                123, // user_guid
                1, // Limit
            ])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                [
                    'batch_id' => 'phpspec',
                    'user_guid' => 123,
                ]
            ]);

        $returned = $this->get($subscription);
        $returned->getUserGuid()
            ->shouldBe($subscription->getUserGuid());
        $returned->getBatchId()
            ->shouldBe($subscription->getBatchId());
    }

    function it_should_add_batch_to_db(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(456)
            ->setBatchId('phpspec');

        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
                456, // user_guid
                'phpspec', // batch_id
            ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($subscription)
            ->shouldBe(true);
    }

    function it_should_remove_from_db(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(789)
            ->setBatchId('phpspec');

        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
                789, // user_guid
                'phpspec', // batch_id
            ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete($subscription)
            ->shouldBe(true);
    }

}
