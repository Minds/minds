<?php

namespace Spec\Minds\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra;
use Minds\Core\Monetization\Manager;

class UsersSpec extends ObjectBehavior
{
    function let(Cassandra\Client $db)
    {
        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Monetization\Users');
    }

    function it_should_set_and_get_an_user_guid_from_literal()
    {
        $this->setUser(10);
        $this->getUser()->shouldReturn(10);
    }

    function it_should_set_and_get_an_user_guid_from_object()
    {
        $this->setUser((object) [ 'guid' => 10 ]);
        $this->getUser()->shouldReturn(10);
    }

    function it_should_get_user_transactions(Manager $manager)
    {
        Di::_()->bind('Monetization\Manager', function($di) use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->get(Argument::that(function($options) {
            return isset($options['user_guid']) && $options['user_guid'] === 10;
        }))->willReturn([
            [ 'guid' => 3, 'amount' => 899 ],
            [ 'guid' => 2, 'amount' => 750 ],
            [ 'guid' => 1, 'amount' => 500 ],
        ]);

        $this->setUser(10);
        $this->getTransactions()->shouldReturn([
            [ 'guid' => 3, 'amount' => 8.99 ],
            [ 'guid' => 2, 'amount' => 7.50 ],
            [ 'guid' => 1, 'amount' => 5.00 ],
        ]);
    }
}
