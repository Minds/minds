<?php

namespace Spec\Minds\Core\Email\Batches;

use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\Repository;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationsSpec extends ObjectBehavior
{
    protected $notificationRepository;
    protected $emailRepository;

    /** @var EntitiesBuilder */
    protected $builder;

    function let(
        Repository $notificationRepository,
        \Minds\Core\Email\Repository $emailRepository,
        EntitiesBuilder $builder
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->emailRepository = $emailRepository;
        $this->builder = $builder;

        Di::_()->bind('Email\Repository', function ($di) use ($emailRepository) {
            return $emailRepository->getWrappedObject();
        });
        Di::_()->bind('EntitiesBuilder', function ($di) use ($builder) {
            return $builder->getWrappedObject();
        });

        $this->beConstructedWith($notificationRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Batches\Notifications');
    }

    function it_should_run()
    {
        $user = new User();
        $user->guid = '123';
        $user->username = 'testuser';

        $subscription = new EmailSubscription();
        $subscription->setUserGuid('123');

        $this->emailRepository->getList([
            'campaign' => 'when',
            'topic' => 'unread_notifications',
            'value' => true,
            'limit' => 200,
            'offset' => ''
        ])
            ->shouldBeCalled()
            ->willReturn(['data' => [$subscription], 'next' => null]);

        $this->builder->get(['guids' => ['123']])
            ->shouldBeCalled()
            ->willReturn([$user]);

        $this->run();
    }
}
