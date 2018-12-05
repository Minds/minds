<?php

namespace Spec\Minds\Core\Notification;

use Minds\Core\Config;
use Minds\Core\Notification\LegacyRepository;
use Minds\Core\Notification\Manager;
use Minds\Core\Notification\Notification;
use Minds\Core\Notification\Repository;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    /** @var Repository */
    private $repository;

    /** @var LegacyRepository */
    private $legacyRepository;

    function let(Config $config, Repository $repository, LegacyRepository $legacyRepository)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->legacyRepository = $legacyRepository;

        $this->beConstructedWith($config, $repository, $legacyRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_a_single_notification(Notification $notification)
    {
        $this->repository->get('1234')
            ->shouldBeCalled()
            ->willReturn($notification);

        $this->getSingle('1234')->shouldReturn($notification);
    }
}
