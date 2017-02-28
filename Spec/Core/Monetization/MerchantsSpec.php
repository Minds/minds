<?php

namespace Spec\Minds\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Di\Di;
use Minds\Core\Monetization;
use Minds\Entities;

class MerchantsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Monetization\Merchants');
    }

    function it_should_set_and_get_a_user(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);

        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    function it_should_get_monetization_on_merchant_users(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->get('ban_monetization')->shouldBeCalled()->willReturn('no');

        $user->getMerchant()->shouldBeCalled()->willReturn([
            'service' => 'stripe',
            'id' => 'phpspec_01'
        ]);

        $this->setUser($user);
        $this->getId()->shouldReturn('phpspec_01');
    }

    function it_should_not_get_monetization_on_non_merchant_users(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->get('ban_monetization')->shouldBeCalled()->willReturn('no');

        $user->getMerchant()->shouldBeCalled()->willReturn([]);

        $this->setUser($user);
        $this->getId()->shouldReturn(false);
    }

    function it_should_not_get_monetization_on_banned_users(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->get('ban_monetization')->shouldBeCalled()->willReturn('yes');

        $user->getMerchant()->shouldNotBeCalled();

        $this->setUser($user);
        $this->getId()->shouldReturn(false);
    }

    function it_should_ban_merchants(
        Monetization\Payouts $payouts,
        Entities\User $user
    )
    {
        Di::_()->bind('Monetization\Payouts', function($di) use ($payouts) {
            return $payouts->getWrappedObject();
        });

        $payouts->setUser($user)->shouldBeCalled();
        $payouts->getLastPayout()->shouldBeCalled()->willReturn(false);
        $payouts->cancel(Argument::cetera())->shouldNotBeCalled();

        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->set('ban_monetization', 'yes')->shouldBeCalled();
        $user->save()->shouldBeCalled()->willReturn(true);

        $this->setUser($user);
        $this->ban()->shouldReturn(true);
    }

    function it_should_ban_merchants_and_cancel_last_payout(
        Monetization\Payouts $payouts,
        Entities\User $user
    )
    {
        Di::_()->bind('Monetization\Payouts', function($di) use ($payouts) {
            return $payouts->getWrappedObject();
        });

        $payouts->setUser($user)->shouldBeCalled();
        $payouts->getLastPayout()->shouldBeCalled()->willReturn([
            'guid' => '1',
            'status' => 'inprogress'
        ]);
        $payouts->cancel('1')->shouldBeCalled();

        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->set('ban_monetization', 'yes')->shouldBeCalled();
        $user->save()->shouldBeCalled()->willReturn(true);

        $this->setUser($user);
        $this->ban()->shouldReturn(true);
    }

    function it_should_unban_merchants(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->set('ban_monetization', 'no')->shouldBeCalled();
        $user->save()->shouldBeCalled()->willReturn(true);

        $this->setUser($user);
        $this->unban()->shouldReturn(true);
    }

    function it_should_check_if_banned(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->get('ban_monetization')->shouldBeCalled()->willReturn('yes');

        $this->setUser($user);
        $this->isBanned()->shouldReturn(true);
    }

    function it_should_check_if_not_banned(Entities\User $user)
    {
        $user->get('guid')->shouldBeCalled()->willReturn(10);
        $user->get('ban_monetization')->shouldBeCalled()->willReturn('no');

        $this->setUser($user);
        $this->isBanned()->shouldReturn(false);
    }
}
