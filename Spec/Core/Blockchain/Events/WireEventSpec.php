<?php

namespace Spec\Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Events\WireEvent;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config;
use Minds\Core\Wire\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WireEventSpec extends ObjectBehavior
{
    /** @var Manager */
    protected $manager;
    /** @var Config */
    protected $config;

    function let(Manager $manager, Config $config)
    {
        $this->beConstructedWith($manager, $config);

        $this->manager = $manager;
        $this->config = $config;

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'contract_address' => '0xasd'
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WireEvent::class);
    }

    function it_should_get_the_topics()
    {
        $this->getTopics()->shouldReturn([
            '0xce785fa87dd60f986617d1c5e02218c5b233399cc29e9a326a41a76fabc95d66',
        ]);
    }

    function it_should_fail_if_address_is_wrong(Transaction $transaction)
    {
        $log = [
            'address' => '0xaaa',
            'data' => [
                '0xs123',
                '0xr123',
                '0x123123'
            ]
        ];
        $this->shouldThrow(new \Exception('Event does not match address'))->during('event',
            ['0xce785fa87dd60f986617d1c5e02218c5b233399cc29e9a326a41a76fabc95d66', $log, $transaction]);

    }

    function it_should_execute_a_wire_sent_event(Transaction $transaction)
    {
        $this->manager->confirm(Argument::type('Minds\Core\Wire\Wire'), $transaction)
            ->shouldBeCalled();

        $log = [
            'address' => '0xasd',
            'data' => [
                '0xs123',
                '0xr123',
                '0x123123'
            ]
        ];

        $this->event('0xce785fa87dd60f986617d1c5e02218c5b233399cc29e9a326a41a76fabc95d66', $log, $transaction);
    }

}
