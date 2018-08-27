<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    function let(Config $config)
    {
        $this->config = $config;

        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
    }

    function it_should_get_the_config()
    {
        $this->config->get(Argument::is('blockchain'))
            ->shouldBeCalled()
            ->willReturn([
                'token_address' => '0x123',
                'contracts' => [
                    'wire' => [
                        'contract_address' => '0x456',
                        'plus_address' => '0xPLUS',
                        'plus_guid' => 123,
                    ],
                    'withdraw' => ['contract_address' => '0x789'],
                    'token_sale_event' => ['contract_address' => '0x987']
                ],
                'boost_address' => '0x654',
                'token_distribution_event_address' => '0x321',

                'network_address' => 'https://rinkeby.infura.io/',
                'client_network' => '1337',
                'wallet_address' => '0x132',
                'boost_wallet_address' => '0x213',
                'eth_rate' => 1,
                'default_gas_price' => 1,
            ]);

        $this->config->get(Argument::is('blockchain_override'))
            ->shouldBeCalled()
            ->willReturn([
                'production' => [
                    'client_network' => '1338',
                ]
            ]);
        $this->setKey('production');

        $this->get()
            ->shouldReturn([
                'token_address' => '0x123',
                'contracts' => [
                    'wire' => [
                        'contract_address' => '0x456',
                        'plus_address' => '0xPLUS',
                        'plus_guid' => 123,
                    ],
                    'withdraw' => [

                        'contract_address' => '0x789'
                    ],
                    'token_sale_event' => [

                        'contract_address' => '0x987'
                    ],
                ],
                'boost_address' => '0x654',
                'token_distribution_event_address' => '0x321',
                'network_address' => 'https://rinkeby.infura.io/',
                'client_network' => '1338',
                'wallet_address' => '0x132',
                'boost_wallet_address' => '0x213',
                'eth_rate' => 1,
                'default_gas_price' => 1
            ]);
    }
}
