<?php

namespace Spec\Minds\Core\Blockchain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Manager;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Contracts\MindsToken;

class TokenSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Token');
    }

    function it_should_return_the_balance(Manager $manager, Ethereum $client, MindsToken $contract)
    {
        $this->beConstructedWith($manager, $client);
        
        $contract->getAddress()->willReturn('minds_token_addr');
        $contract->getExtra()->willReturn([ 'decimals' => 18 ]);

        $manager->getContract('token')->willReturn($contract);

        $client->call('minds_token_addr', 'balanceOf(address)', [ 'foo' ])
            ->willReturn('0x2B5E3AF16B1880000');

        $this->balanceOf('foo')->shouldBe('50000000000000000000');
    }

}
