<?php

namespace Spec\Minds\Core\Channels\Delegates;

use Minds\Core\Queue\RabbitMQ\Client as QueueClient;
use Minds\Core\Channels\Delegates\DeleteArtifacts;
use Minds\Entities\User;
use Minds\Core\Data\Call;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteArtifactsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteArtifacts::class);
    }

    function it_should_queue_the_delete_job(QueueClient $queue)
    {
        $this->beConstructedWith($queue);

        $queue->setQueue('ChannelDeleteArtifactsCleanup')
            ->shouldBeCalled()
            ->willReturn($queue);

        $user = new User;
        $user->guid = 123;

        $queue
            ->send([
                'user_guid' => (string) 123,
            ])
            ->shouldBeCalled();

        $this->queue($user);
    }

    function it_should_delete_artifacts(
        QueueClient $queue,
        Call $indexes,
        Call $entities,
        Call $subscriptions,
        Call $subscribers
    )
    {
        $this->beConstructedWith($queue, $indexes, $entities, $subscriptions, $subscribers);

        $post_guids = [
            10001 => time(),
            10002 => time(),
            10003 => time(),
            10004 => time(),
            10005 => time(),
        ];

        $indexes
            ->getRow("activity:user:123", [
                'offset' => "",
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn($post_guids);
        
        $indexes
            ->getRow("activity:user:123", [
                'offset' => 10005,
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([ 10006 => time() ]);
        
        //end the while loop by returning no results
        $indexes
            ->getRow("activity:user:123", [
                'offset' => 10006,
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([ ]);

        $indexes->getRow("object:blog:user:123", [
            'offset' => "",
            'limit' => 500
        ])
            ->shouldBeCalled();

        $indexes->getRow("object:image:user:123", [
            'offset' => "",
            'limit' => 500
        ])
            ->shouldBeCalled();

        $indexes->getRow("object:video:user:123", [
            'offset' => "",
            'limit' => 500
        ])
            ->shouldBeCalled();

        foreach ($post_guids as $guid => $ts) {
            $entities
                ->removeRow($guid)
                ->shouldBeCalled();
            $indexes
                ->removeAttributes("activity:user:123", [ $guid ])
                ->shouldBeCalled();
        }

        //this is used to check the while loop works
        $entities
            ->removeRow(10006)
            ->shouldBeCalled();
        $indexes
            ->removeAttributes("activity:user:123", [ 10006 ])
            ->shouldBeCalled();

        /**
         * Subscriptions
         */

        $subscriptions->getRow(123, [
                'offset' => '',
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([
                20001 => time()
            ]);

        //cancel while loop
        $subscriptions->getRow(123, [
                'offset' => 20001,
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([ ]);

        $subscribers->removeAttributes(20001, [ 123 ])
            ->shouldBeCalled();

        $subscriptions->removeAttributes(123, [ 20001 ])
            ->shouldBeCalled();

        $subscriptions->removeRow(123)
            ->shouldBeCalled();

        /**
         * Subscribers
         */
        $subscribers->getRow(123, [
                'offset' => '',
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([
                30001 => time()
            ]);

        //cancel while loop
        $subscribers->getRow(123, [
                'offset' => 30001,
                'limit' => 500
            ])
            ->shouldBeCalled()
            ->willReturn([ ]);

        $subscriptions->removeAttributes(30001, [ 123 ])
            ->shouldBeCalled();

        $subscribers->removeAttributes(123, [ 30001 ])
            ->shouldBeCalled();

        $subscribers->removeRow(123)
            ->shouldBeCalled();

        $this->delete(123);
    }

}
