<?php

namespace Spec\Minds\Core\Analytics\UserStates;

use Minds\Core\Analytics\UserStates\Manager;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Analytics\UserStates\ActiveUsersIterator;
use Minds\Core\Analytics\UserStates\UserStateIterator;
use Minds\Core\Queue;
use PhpSpec\ObjectBehavior;
use Minds\Core\Data\ElasticSearch\Prepared;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    protected $activeUsersIterator;
    protected $userStateIterator;
    protected $queue;

    public function let(Client $client, Queue\RabbitMQ\Client $queue)
    {
        $activeUsersIterator = new ActiveUsersIterator($client->getWrappedObject());
        $userStateIterator = new UserStateIterator($client->getWrappedObject());
        $this->beConstructedWith($client, 'minds-metrics-*', $queue, $activeUsersIterator, $userStateIterator);
        $this->client = $client;
        $this->queue = $queue;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    public function it_should_sync()
    {
        $referenceDate = 1549497600;

        $this->setReferenceDate($referenceDate);
        $this->setRangeOffset(7);

        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->getMockData('active_users_results.json'));

        $this->client->bulk(Argument::size(1))
            ->shouldBeCalled();

        $this->sync();
    }

    public function it_should_emit_state_changes()
    {
        $referenceDate = 1549497600;
        $this->setReferenceDate($referenceDate);
        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->getMockData('user_state_changes_results.json'));

        $this->queue->setQueue('UserStateChanges')->shouldBeCalled();

        $this->queue->send($this->getMockQueueMessage())->shouldBeCalled();

        $this->client->bulk(Argument::size(1))
            ->shouldBeCalled();
        $this->emitStateChanges();
    }

    private function getMockQueueMessage()
    {
        return [
            'user_state_change' => [
                'user_guid' => '933120961241157645',
                'reference_date' => 1549238400000,
                'state' => 'curious',
                'previous_state' => 'resurrected',
                'activity_percentage' => '0.14',
            ],
        ];
    }

    private function getMockData($filename)
    {
        return json_decode(file_get_contents(__DIR__."/MockData/${filename}"), true);
    }
}
