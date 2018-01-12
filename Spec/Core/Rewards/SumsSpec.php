<?php

namespace Spec\Minds\Core\Rewards;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;

class SumsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Sums');
    }

    function it_sould_get_a_balance(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::any())
            ->willReturn([
                [ 'balance' => 12 ]
            ]);

        $this->getBalance()
            ->shouldReturn((double) 12);
    }

}
