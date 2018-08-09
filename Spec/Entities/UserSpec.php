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

}
