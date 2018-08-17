<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\GasPrice;
use Minds\Core\Http\Curl\Json\Client;
use PhpSpec\ObjectBehavior;

class GasPriceSpec extends ObjectBehavior
{
    /** @var Client */
    private $client;

    function let(Client $client)
    {
        $this->client = $client;

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GasPrice::class);
    }

    function it_should_get_latest_gas_price()
    {
        $this->client->get('https://api.infura.io/v1/jsonrpc/mainnet/eth_gasPrice')
            ->shouldBeCalled()
            ->willReturn(["jsonrpc" => "2.0", "id" => 1, "result" => "0xee6b2800"]);

        $this->getLatestGasPrice()->shouldReturn('0xee6b2800');
    }

    function it_should_fail_to_get_the_gas_price()
    {
        $this->client->get('https://api.infura.io/v1/jsonrpc/mainnet/eth_gasPrice')
            ->shouldBeCalled()
            ->willReturn('');

        $this->getLatestGasPrice()->shouldReturn('0x3b9aca00');
    }
}
