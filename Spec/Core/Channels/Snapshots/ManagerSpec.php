<?php

namespace Spec\Minds\Core\Channels\Snapshots;

use Minds\Core\Channels\Delegates\Artifacts\ArtifactsDelegateInterface;
use Minds\Core\Channels\Delegates\Artifacts\Factory;
use Minds\Core\Channels\Snapshots\Manager;
use Minds\Core\Channels\Snapshots\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var Factory */
    protected $artifactsDelegatesFactory;

    function let(
        Repository $repository,
        Factory $artifactsDelegatesFactory
    )
    {
        $this->beConstructedWith($repository, $artifactsDelegatesFactory);
        $this->repository = $repository;
        $this->artifactsDelegatesFactory = $artifactsDelegatesFactory;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_snapshot(
        ArtifactsDelegateInterface $artifactsDelegateMock
    )
    {
        $delegates = [
            'PHPSpec1',
            'PHPSpec2',
        ];

        foreach ($delegates as $delegate) {
            $this->artifactsDelegatesFactory->build($delegate)
                ->shouldBeCalled()
                ->willReturn($artifactsDelegateMock);
        }

        $artifactsDelegateMock->snapshot(1000)
            ->shouldBeCalledTimes(count($delegates))
            ->willReturn(true);

        $this
            ->setUserGuid(1000)
            ->snapshot($delegates)
            ->shouldReturn(true);
    }


    function it_should_restore(
        ArtifactsDelegateInterface $artifactsDelegateMock
    )
    {
        $delegates = [
            'PHPSpec1',
            'PHPSpec2',
        ];

        foreach ($delegates as $delegate) {
            $this->artifactsDelegatesFactory->build($delegate)
                ->shouldBeCalled()
                ->willReturn($artifactsDelegateMock);
        }

        $artifactsDelegateMock->restore(1000)
            ->shouldBeCalledTimes(count($delegates))
            ->willReturn(true);

        $this
            ->setUserGuid(1000)
            ->restore($delegates)
            ->shouldReturn(true);
    }

    function it_should_get_all()
    {
        $this->repository->getList([
            'user_guid' => 1000,
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setUserGuid(1000)
            ->getAll()
            ->shouldReturn(true);
    }

    function it_should_truncate()
    {
        $this->repository->deleteAll(1000, null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setUserGuid(1000)
            ->truncate(null)
            ->shouldReturn(true);
    }
}
