<?php

namespace Spec\Minds\Core\Notification;

use Minds\Core\Config;
use Minds\Core\Notification\LegacyRepository;
use Minds\Core\Notification\Manager;
use Minds\Core\Notification\Notification;
use Minds\Core\Notification\Repository;
use Minds\Core\Notification\CassandraRepository;
use Minds\Core\Features\Manager as FeaturesManager;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    /** @var Repository */
    private $repository;

    /** @var CassandraRepository */
    private $cassandraRepository;

    /** @var FeaturesManager */
    private $features;

    function let(
        Config $config,
        Repository $repository,
        CassandraRepository $cassandraRepository,
        FeaturesManager $features
    )
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->cassandraRepository = $cassandraRepository;
        $this->features = $features;

        $this->beConstructedWith($config, $repository, $cassandraRepository, $features);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_a_single_notification(Notification $notification, User $user)
    {
        $this->setUser($user);
        $user->getGUID()
            ->willReturn(456);

        $this->cassandraRepository->get('urn:notification:456-1234')
            ->shouldBeCalled()
            ->willReturn($notification);

        $this->getSingle('1234')->shouldReturn($notification);
    }

    function it_should_get_from_cassandra_if_urn_provided(Notification $notification)
    {
        $this->cassandraRepository->get('urn:notification:1234')
            ->shouldBeCalled()
            ->willReturn($notification);

        $this->getSingle('urn:notification:1234')->shouldReturn($notification);
    }

    function it_should_get_list_from_cassandra_if_feature_enabled(Notification $notification)
    {
        $this->features->has('cassandra-notifications')
            ->willReturn(true);

        $this->cassandraRepository->getList(Argument::that(function($opts) {
            return $opts['limit'] === 6;
        }))
            ->shouldBeCalled()
            ->willReturn([ $notification ]);

        $response = $this->getList([ 'limit' => 6 ]);
        $response[0]->shouldBe($notification);
    }

    function it_should_add_to_both_repositories(Notification $notification)
    {
        $this->repository->add($notification)
            ->shouldBeCalled();

        $this->cassandraRepository->add($notification)
            ->shouldBeCalled();

        $this->add($notification);
    }
}
