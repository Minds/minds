<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Contracts\MindsTokenSaleEvent;
use Minds\Core\Blockchain\Manager;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\TokenDistributionEvent;
use PhpSpec\ObjectBehavior;

class TokenDistributionEventSpec extends ObjectBehavior
{
    /** @var Manager */
    private $manager;
    /** @var  Ethereum */
    private $client;
    /** @var MindsTokenSaleEvent */
    private $contract;

    function let(Manager $manager, Ethereum $client, MindsTokenSaleEvent $contract)
    {
        $this->manager = $manager;
        $this->client = $client;
        $this->contract = $contract;

        $this->manager->getContract('token_distribution_event')
            ->shouldBeCalled()
            ->willReturn($contract);

        $contract->getAddress()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->beConstructedWith($manager, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TokenDistributionEvent::class);
    }

    function it_should_get_the_rate()
    {
        $this->client->call('0x123', 'rate()', [])
            ->shouldBeCalled()
            ->willReturn('0xFF');

        $this->rate()->shouldReturn('255');
    }

    function it_should_get_the_total_of_eth_raised()
    {
        $this->client->call('0x123', 'weiRaised()', [])
            ->shouldBeCalled()
            ->willReturn('0xDE0B6B3A7640000');

        $this->raised()->shouldReturn(1.0);
    }

    function it_should_get_the_end_time_of_the_event()
    {
        $this->client->call('0x123', 'endTime()', [])
            ->shouldBeCalled()
            ->willReturn('0xFF');

        $this->endTime()->shouldReturn(255);
    }
}
