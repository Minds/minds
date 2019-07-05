<?php

namespace Spec\Minds\Core\Channels;

use Minds\Core\Channels\Delegates\Artifacts\ArtifactsDelegateInterface;
use Minds\Core\Channels\Ban;
use Minds\Core\Channels\Delegates;
use Minds\Core\Channels\Delegates\Artifacts;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Entities\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BanSpec extends ObjectBehavior
{
    /** @var string[] */
    protected $artifactsDelegates;

    /** @var Delegates\Artifacts\Factory */
    protected $artifactsDelegatesFactory;

    /** @var Delegates\Logout */
    protected $logoutDelegate;

    /** @var Delegates\Ban */
    protected $banDelegate;

    /** @var  Delegates\Unban */
    protected $unbanDelegate;

    /** @var  QueueClient */
    protected $queueClient;

    function let(
        Delegates\Artifacts\Factory $artifactsDelegatesFactory,
        Delegates\Logout $logoutDelegate,
        Delegates\Ban $banDelegate,
        Delegates\Unban $unbanDelegate,
        QueueClient $queueClient
    )
    {
        $this->beConstructedWith(
            $artifactsDelegatesFactory,
            $logoutDelegate,
            $banDelegate,
            $unbanDelegate,
            $queueClient
        );

        $this->artifactsDelegatesFactory = $artifactsDelegatesFactory;
        $this->logoutDelegate = $logoutDelegate;
        $this->banDelegate = $banDelegate;
        $this->unbanDelegate = $unbanDelegate;
        $this->queueClient = $queueClient;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Ban::class);
    }

    function it_should_ban_a_channel(
        User $user
    )
    {
        $this->banDelegate->ban($user, 'phpspec')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->logoutDelegate->logout($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->queueClient->setQueue('ChannelDeferredOps')
            ->shouldBeCalled()
            ->willReturn($this->queueClient);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->queueClient->send([
            'type' => 'ban',
            'user_guid' => 1000
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setUser($user)
            ->ban('phpspec')
            ->shouldReturn(true);
    }

    function it_should_clean_up_a_banned_channel(
        User $user,
        ArtifactsDelegateInterface $artifactsDelegateMock
    )
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $user->isBanned()
            ->shouldBeCalled()
            ->willReturn(true);

        $deletionDelegates = [
            Artifacts\UserEntitiesDelegate::class,
            Artifacts\SubscribersDelegate::class,
            Artifacts\SubscriptionsDelegate::class,
            Artifacts\ElasticsearchDocumentsDelegate::class,
            Artifacts\CommentsDelegate::class,
        ];

        foreach ($deletionDelegates as $deletionDelegate) {
            $this->artifactsDelegatesFactory->build($deletionDelegate)
                ->shouldBeCalled()
                ->willReturn($artifactsDelegateMock);
        }

        $artifactsDelegateMock->snapshot(1000)
            ->shouldBeCalledTimes(count($deletionDelegates))
            ->willReturn(true);

        $artifactsDelegateMock->hide(1000)
            ->shouldBeCalledTimes(count($deletionDelegates))
            ->willReturn(true);

        $this
            ->setUser($user)
            ->banCleanup()
            ->shouldReturn(true);
    }

    function it_should_unban_a_channel(
        User $user
    )
    {
        $this->unbanDelegate->unban($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->queueClient->setQueue('ChannelDeferredOps')
            ->shouldBeCalled()
            ->willReturn($this->queueClient);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->queueClient->send([
            'type' => 'unban',
            'user_guid' => 1000
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setUser($user)
            ->unban()
            ->shouldReturn(true);
    }

    function it_should_restore_an_unbanned_channel(
        User $user,
        ArtifactsDelegateInterface $artifactsDelegateMock
    )
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $user->isBanned()
            ->shouldBeCalled()
            ->willReturn(false);

        $deletionDelegates = [
            Artifacts\UserEntitiesDelegate::class,
            Artifacts\SubscribersDelegate::class,
            Artifacts\SubscriptionsDelegate::class,
            Artifacts\ElasticsearchDocumentsDelegate::class,
            Artifacts\CommentsDelegate::class,
        ];

        foreach ($deletionDelegates as $deletionDelegate) {
            $this->artifactsDelegatesFactory->build($deletionDelegate)
                ->shouldBeCalled()
                ->willReturn($artifactsDelegateMock);
        }

        $artifactsDelegateMock->restore(1000)
            ->shouldBeCalledTimes(count($deletionDelegates))
            ->willReturn(true);

        $this
            ->setUser($user)
            ->unbanRestore()
            ->shouldReturn(true);
    }
}
