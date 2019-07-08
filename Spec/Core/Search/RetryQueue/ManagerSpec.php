<?php

namespace Spec\Minds\Core\Search\RetryQueue;

use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Search\RetryQueue\Manager;
use Minds\Core\Search\RetryQueue\Repository;
use Minds\Core\Search\RetryQueue\RetryQueueEntry;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    /** @var Repository */
    protected $repository;

    function let(
        EventsDispatcher $eventsDispatcher,
        Repository $repository
    )
    {
        $this->beConstructedWith($eventsDispatcher, $repository);

        $this->eventsDispatcher = $eventsDispatcher;
        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_prune(Entity $entity)
    {
        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn('5000');

        $this->repository->delete(Argument::type(RetryQueueEntry::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->prune($entity)
            ->shouldReturn(true);
    }

    function it_should_retry(Entity $entity, RetryQueueEntry $retryQueueEntry)
    {
        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn('5000');

        $this->repository->get('urn:entity:5000')
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $retryQueueEntry->getRetries()
            ->shouldBeCalled()
            ->willReturn(3);

        $retryQueueEntry->setLastRetry(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $retryQueueEntry->setRetries(4)
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $this->repository->add($retryQueueEntry)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->eventsDispatcher->trigger('search:index', 'all', [
            'entity' => $entity
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->retry($entity)
            ->shouldReturn(true);
    }

    function it_should_not_retry_if_too_many_attempts(Entity $entity, RetryQueueEntry $retryQueueEntry)
    {
        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn('5000');

        $this->repository->get('urn:entity:5000')
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $retryQueueEntry->getRetries()
            ->shouldBeCalled()
            ->willReturn(4);

        $retryQueueEntry->setLastRetry(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $retryQueueEntry->setRetries(5)
            ->shouldBeCalled()
            ->willReturn($retryQueueEntry);

        $this->repository->add($retryQueueEntry)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->eventsDispatcher->trigger('search:index', Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->retry($entity)
            ->shouldReturn(true);
    }
}
