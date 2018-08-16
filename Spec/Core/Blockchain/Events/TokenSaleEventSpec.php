<?php

namespace Spec\Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Events\TokenSaleEvent;
use Minds\Core\Blockchain\Purchase;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSaleEventSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    /** @var Purchase\Manager */
    private $manager;

    function let(Config $config, Purchase\Manager $manager)
    {
        $this->config = $config;
        $this->manager = $manager;
        $this->beConstructedWith($config, $manager);

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'token_sale_event' => [
                        'contract_address' => '0xasd'
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TokenSaleEvent::class);
    }

    function it_should_get_the_topics()
    {
        $this->getTopics()->shouldReturn([
            '0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861',
            'blockchain:fail'
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
            ['0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction]);

    }

    function it_should_execute_a_token_purchase_event(Transaction $transaction, Purchase\Purchase $purchase)
    {
        $log = [
            'address' => '0xasd',
            'data' => [
                '0xp123',
                '0xFF',
            ]
        ];

        $transaction->getAmount()
            ->shouldBeCalled()
            ->willReturn(255);

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn(['phone_number_hash' => 'hash']);
        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->manager->getPurchase('hash', '0xTX')
            ->shouldBeCalled()
            ->willReturn($purchase);

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(256);

        $this->manager->getAutoIssueCap()
            ->shouldBeCalled()
            ->willReturn(257);

        $this->manager->issue(Argument::type('Minds\Core\Blockchain\Purchase\Purchase'))
            ->shouldBeCalled();

        $this->event('0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction);
    }

    function it_shouldnt_execute_a_token_purchase_event_if_ammounts_differ(Transaction $transaction)
    {
        $log = [
            'address' => '0xasd',
            'data' => [
                '0xp123',
                '0xFA',
            ]
        ];

        $transaction->getAmount()
            ->shouldBeCalled()
            ->willReturn(255);

        $this->manager->issue(Argument::type('Minds\Core\Blockchain\Purchase\Purchase'))
            ->shouldNotBeCalled();

        $this->event('0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction);
    }

    function it_shouldnt_execute_a_token_purchase_event_if_purchase_isnt_found(Transaction $transaction)
    {
        $log = [
            'address' => '0xasd',
            'data' => [
                '0xp123',
                '0xFF',
            ]
        ];

        $transaction->getAmount()
            ->shouldBeCalled()
            ->willReturn(255);

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn(['phone_number_hash' => 'hash']);
        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->manager->getPurchase('hash', '0xTX')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->manager->issue(Argument::type('Minds\Core\Blockchain\Purchase\Purchase'))
            ->shouldNotBeCalled();

        $this->event('0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction);
    }

    function it_shouldnt_execute_a_token_purchase_event_if_requested_more_that_can_be_issued(
        Transaction $transaction,
        Purchase\Purchase $purchase
    ) {
        $log = [
            'address' => '0xasd',
            'data' => [
                '0xp123',
                '0xFF',
            ]
        ];

        $transaction->getAmount()
            ->shouldBeCalled()
            ->willReturn(255);

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn(['phone_number_hash' => 'hash']);
        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->manager->getPurchase('hash', '0xTX')
            ->shouldBeCalled()
            ->willReturn($purchase);

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(254);

        $this->manager->issue(Argument::type('Minds\Core\Blockchain\Purchase\Purchase'))
            ->shouldNotBeCalled();

        $this->event('0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction);
    }

    function it_shouldnt_execute_a_token_purchase_event_if_unissued_amount_if_greater_than(
        Transaction $transaction,
        Purchase\Purchase $purchase
    ) {
        $log = [
            'address' => '0xasd',
            'data' => [
                '0xp123',
                '0xFF',
            ]
        ];

        $transaction->getAmount()
            ->shouldBeCalled()
            ->willReturn(255);

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn(['phone_number_hash' => 'hash']);
        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->manager->getPurchase('hash', '0xTX')
            ->shouldBeCalled()
            ->willReturn($purchase);

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(254);

        $this->manager->issue(Argument::type('Minds\Core\Blockchain\Purchase\Purchase'))
            ->shouldNotBeCalled();

        $this->event('0xf4b351c7293f3c20fc9912c61adbe9823a6de3162bde18c98eb6feeae232f861', $log, $transaction);
    }
}
