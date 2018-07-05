<?php

namespace Spec\Minds\Core\Boost\Peer;

use Minds\Core\Boost\Peer\Review;
use Minds\Core\Boost\Repository;
use Minds\Core\Data\MongoDB;
use Minds\Entities\Boost\Peer;
use PhpSpec\ObjectBehavior;

class ReviewSpec extends ObjectBehavior
{
    /** @var MongoDB\Client */
    protected $client;
    /** @var Repository */
    protected $repository;

    function let(MongoDB\Client $client, Repository $repository)
    {
        $this->beConstructedWith($client, $repository);
        $this->client = $client;
        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Review::class);
    }

    function it_should_accept_a_boost(Peer $boost)
    {
        $boost->setState('accepted')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled();

        $this->setBoost($boost);

        $this->accept();
    }

    function it_should_reject_a_boost(Peer $boost)
    {
        $boost->setState('rejected')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled();

        $this->setBoost($boost);

        $this->reject();
    }

    function it_should_revoke_a_boost(Peer $boost)
    {
        $boost->setState('revoked')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled();

        $this->setBoost($boost);

        $this->revoke();
    }

    function it_should_return_the_boost_entity(Peer $boost)
    {
        $guid = '123';

        $this->repository->getEntity('peer', $guid)
            ->shouldBeCalled()
            ->willReturn($boost);

        $this->getBoostEntity($guid)->shouldReturnAnInstanceOf('Minds\\Entities\\Boost\\Peer');
    }

    function it_should_return_the_review_queue(Peer $boost1, Peer $boost2)
    {
        $type = '123';
        $limit = 500;
        $offset = '';

        $this->repository->getAll('peer', [
            'destination_guid' => $type,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'ASC'
        ])
            ->shouldBeCalled()
            ->willReturn([$boost1, $boost2]);

        $this->setType($type);
        $this->getReviewQueue($limit, $offset)->shouldReturn([$boost1, $boost2]);
    }

    function it_should_get_the_outbox(Peer $boost1, Peer $boost2)
    {
        $guid = '123';
        $limit = 500;
        $offset = '';

        $this->repository->getAll('peer', [
            'owner_guid' => $guid,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'DESC'
        ])
            ->shouldBeCalled()
            ->willReturn([$boost1, $boost2]);

        $this->getOutbox($guid, $limit, $offset)->shouldReturn([$boost1, $boost2]);
    }
}
