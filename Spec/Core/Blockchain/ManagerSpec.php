<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Contracts\ExportableContract;
use Minds\Core\Blockchain\Manager;
use Minds\Core\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    function let(Config $config)
    {
        $this->config = $config;

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
                    'token_sale_event' => ['contract_address' => '0x987'],
                    'boost' => ['contract_address' => '0x002', 'wallet_address' => '0x003']
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

        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_a_contract()
    {
        $this->getContract('token')->shouldReturnAnInstanceOf(ExportableContract::class);
    }

    function it_should_return_null_if_contract_wasnt_found()
    {
        $this->getContract('not_found')->shouldReturn(null);
    }

    function it_should_get_public_settings()
    {
        $this->config->get('site_url')
            ->shouldBeCalled()
            ->willReturn('www.minds.com/');

        $this->config->get('blockchain_override')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->getPublicSettings()
            ->shouldBeArray();
    }

    function it_should_get_overrides()
    {
        $this->config->get(Argument::is('blockchain_override'))
            ->shouldBeCalled()
            ->willReturn([
                'production' => [
                    'client_network' => '1338',
                ]
            ]);

        $this->getOverrides()
            ->shouldReturn([
                'production' => [
                    'network_address' => "https://rinkeby.infura.io/",
                    'client_network' => "1338",
                    'wallet_address' => "0x132",
                    'boost_wallet_address' => "0x003",
                    'token_distribution_event_address' => "0x987",
                    'plus_address' => '0xPLUS',
                    'default_gas_price' => 1,
                ]
            ]);
    }

    function it_should_get_the_rate()
    {
        $this->getRate()->shouldReturn(1000);
    }
}
