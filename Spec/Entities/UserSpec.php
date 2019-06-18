<?php

namespace Spec\Minds\Entities;

use Minds\Entities\User;
use Minds\Core\Di\Di;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_should_not_return_admin_if_not_whitelisted()
    {
        //remove ip whitelist check
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '10.56.0.10';

        $this->admin = 'yes';
        $this->isAdmin()->shouldBe(false);
        //Di::_()->get('Config')->set('admin_ip_whitelist', [ '10.56.0.1' ]);
    }

    function it_should_return_admin_if_whitelisted()
    {
        //remove ip whitelist check
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '10.56.0.1';
        Di::_()->get('Config')->set('admin_ip_whitelist', [ '10.56.0.1' ]);

        $this->admin = 'yes';
        $this->isAdmin()->shouldBe(true);
    }

    function it_should_assign_the_onchain_booster_status() {
        $this->setOnchainBooster(123);
        $this->getOnchainBooster()->shouldReturn(123);
    }

    function it_should_recognise_a_user_is_in_the_onchain_booster_timeframe() {
        $this->setOnchainBooster(20601923579999);
        $this->isOnchainBooster()->shouldReturn(true);
    }

    function it_should_recognise_a_user_is_not_in_the_onchain_booster_timeframe() {
        $this->setOnchainBooster(1560192357);
        $this->isOnchainBooster()->shouldReturn(false);
    }
}
