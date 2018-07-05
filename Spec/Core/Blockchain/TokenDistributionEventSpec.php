<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Contracts\MindsTokenSaleEvent;
use Minds\Core\Blockchain\Manager;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\TokenDistributionEvent;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;

class TokenDistributionEventSpec extends ObjectBehavior
{
    /** @var Manager */
    protected $manager;
    /** @var Ethereum */
    protected $client;

    function let(Manager $manager, Ethereum $client, MindsTokenSaleEvent $contract)
    {
        $this->manager = $manager;
        $this->client = $client;

        $this->manager->getContract('token_distribution_event')
            ->willReturn($contract);

        $contract->getAddress()
            ->willReturn('0x1234');

        $this->beConstructedWith($manager, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TokenDistributionEvent::class);
    }

    function it_should_get_the_token_exchange_rate()
    {
        $this->client->call('0x1234', 'rate()', [])
            ->shouldBeCalled()
            ->willReturn(BigNumber::_(BigNumber::fromPlain(10 ** 18, 18))->toHex());

        $this->rate()->shouldReturn('1');
    }

    function it_should_get_the_total_of_eth_raised()
    {
        $this->client->call('0x1234', 'weiRaised()', [])
            ->shouldBeCalled()
            ->willReturn(BigNumber::_(BigNumber::fromPlain(10 ** 18, 18))->toHex());

        $this->raised()->shouldReturn((double) 10 ** -18);
    }

    function it_should_get_the_end_time_of_the_event()
    {
        $time = '0xBC614E';
        $this->client->call('0x1234', 'endTime()', [])
            ->shouldBeCalled()
            ->willReturn($time);

        $this->endTime()->shouldReturn(12345678);
    }
}
