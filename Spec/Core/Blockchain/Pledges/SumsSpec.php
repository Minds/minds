<?php

namespace Spec\Minds\Core\Blockchain\Pledges;

use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;

class SumsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Pledges\Sums');
    }

    function it_sould_get_the_total_amount(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::any())
            ->willReturn([
                [ 'amount' => 12000 ]
            ]);

        $this->getTotalAmount()
            ->shouldReturn('12000');
    }

    function it_sould_get_the_total_count(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::any())
            ->willReturn([
                [ 'count' => 12 ]
            ]);

        $this->getTotalCount()
            ->shouldReturn('12');
    }

}
