<?php

namespace Spec\Minds\Core\Notification\Batches;

use Minds\Core\Notification\Batches\Manager;
use Minds\Core\Notification\Batches\Repository;
use Minds\Core\Notification\Batches\BatchSubscription;
use Minds\Entities\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    protected $repository;

    function let(Repository $repository)
    {
        $this->repository = $repository;
        $this->beConstructedWith(null, $repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_true_if_subscribed()
    {
        $user = new User;
        $user->set('guid', 123);

        $this->setUser($user);
        $this->setBatchId('phpspec');

        $this->repository->get(
            Argument::that(function($subscription) {
                return $subscription->getUserGuid() == 123
                    && $subscription->getBatchId() == 'phpspec';
            }))
            ->willReturn(true);

        $this->isSubscribed()->shouldBe(true);
    }

    function it_should_return_false_if_not_subscribed()
    {
        $this->setUser(456);
        $this->setBatchId('phpspec');

        $this->repository->get(
            Argument::that(function($subscription) {
                return $subscription->getUserGuid() == 456
                    && $subscription->getBatchId() == 'phpspec';
            }))
            ->willReturn(false);

        $this->isSubscribed()->shouldBe(false);
    }

    function it_should_subscribe()
    {
        $this->setUser(789);
        $this->setBatchId('phpspec');

        $this->repository->add(
            Argument::that(function($subscription) {
                return $subscription->getUserGuid() == 789
                    && $subscription->getBatchId() == 'phpspec';
            }))
            ->willReturn(true);

        $this->subscribe()->shouldBe(true);
    }

    function it_should_unsubscribe()
    {
        $this->setUser(101112);
        $this->setBatchId('phpspec');

        $this->repository->delete(
            Argument::that(function($subscription) {
                return $subscription->getUserGuid() == 101112
                    && $subscription->getBatchId() == 'phpspec';
            }))
            ->willReturn(true);

        $this->unSubscribe()->shouldBe(true);
    }

}
