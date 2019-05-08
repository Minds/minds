<?php

namespace Spec\Minds\Core\Email\Batches;

use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\Repository;
use Minds\Core\Notification\Counters;
use Minds\Common\Repository\Response;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class NotificationsSpec extends ObjectBehavior
{
    protected $notificationRepository;
    protected $emailRepository;
    protected $counters;

    /** @var EntitiesBuilder */
    protected $builder;

    public function let(
        Repository $notificationRepository,
        \Minds\Core\Email\Repository $emailRepository,
        EntitiesBuilder $builder,
        Counters $counters
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->emailRepository = $emailRepository;
        $this->builder = $builder;
        $this->counters = $counters;

        Di::_()->bind('Email\Repository', function ($di) use ($emailRepository) {
            return $emailRepository->getWrappedObject();
        });
        Di::_()->bind('EntitiesBuilder', function ($di) use ($builder) {
            return $builder->getWrappedObject();
        });

        $this->beConstructedWith($notificationRepository, $counters);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Batches\Notifications');
    }

    public function it_should_run()
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
            'offset' => '',
        ])
            ->shouldBeCalled()
            ->willReturn(['data' => [$subscription], 'next' => null]);

        $this->counters->setUser('123')->shouldBeCalled();
        $this->counters->getCount()->willReturn(1);

        $this->notificationRepository->getList([
            'limit' => 1,
            'to_guid' => 123,
        ])
            ->shouldBeCalled()
            ->willReturn(new Response());

        $this->builder->get(['guids' => ['123']])
            ->shouldBeCalled()
            ->willReturn([$user]);

        $this->run();
    }
}
