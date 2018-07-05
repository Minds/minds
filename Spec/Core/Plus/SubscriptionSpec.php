<?php

namespace Spec\Minds\Core\Plus;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Payments\Stripe\Stripe;
use Minds\Core\Payments\Subscriptions\Manager;
use Minds\Core\Payments\Subscriptions\Repository;
use Minds\Core\Payments\Subscriptions\Subscription;
use Minds\Entities\User;

class SubscriptionSpec extends ObjectBehavior
{
    /** @var Stripe */
    protected $stripe;
    /** @var Manager */
    protected $manager;
    /** @var Repository */
    protected $repo;

    function let(
        Stripe $stripe,
        Manager $manager,
        Repository $repo
    ) {
        $this->beConstructedWith($stripe, $manager, $repo);

        $this->stripe = $stripe;
        $this->manager = $manager;
        $this->repo = $repo;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Plus\Subscription');
    }

    function it_should_return_if_a_subscription_is_active(
        Subscription $subscription
    ) {
        $this->repo->getList(Argument::any())->willReturn([
            $subscription
        ]);

        $subscription->getStatus()->willReturn('active');

        $user = new User();
        $user->guid = 123;

        $this->setUser($user);
        $this->isActive()->shouldBe(true);
    }

    function it_should_return_false_if_a_subscription_is_active(
        Subscription $subscription
    ) {
        $this->repo->getList(Argument::any())->willReturn([
            $subscription
        ]);

        $subscription->getStatus()->willReturn('cancelled');

        $user = new User();
        $user->guid = 123;

        $this->setUser($user);
        $this->isActive()->shouldBe(false);
    }

    function it_should_create_a_new_subscription(
        Subscription $subscription
    ) {
        $subscription->setInterval('monthly')
            ->shouldBeCalled()
            ->willReturn($subscription);

        $subscription->setAmount(5)
            ->shouldBeCalled()
            ->willReturn($subscription);

        $this->manager->setSubscription($subscription)
            ->shouldBeCalled()
            ->willReturn($this->manager);

        $this->manager->create()
            ->shouldBeCalled();

        $this->create($subscription)
            ->shouldReturn($this);
    }

    function it_should_cancel_existing_subscription(
        Subscription $subscription
    ) {
        $this->repo->getList([
            'plan_id' => 'plus',
            'payment_method' => 'money',
            'user_guid' => 123
        ])
            ->shouldBeCalled()
            ->willReturn([$subscription]);

        $this->stripe->cancelSubscription($subscription)
            ->shouldBeCalled();

        $this->manager->setSubscription($subscription)
            ->shouldBeCalled()
            ->willReturn($this->manager);

        $this->manager->cancel()
            ->shouldBeCalled();

        $user = new User();
        $user->guid = 123;

        $this->setUser($user);

        $this->cancel()
            ->shouldReturn($this);
    }

}
