<?php

namespace Spec\Minds\Core\Rewards\Withdraw;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Pending;
use Minds\Core\Rewards\Withdraw\Request;
use Minds\Core\Rewards\Transactions;
use Minds\Core\Blockchain\Util;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Config\Config;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Withdraw\Manager');
    }

    function it_should_allow_a_withdrawl_request_to_be_made(Pending $pending)
    {
        $this->beConstructedWith($pending);

        $pending->add([
            'type' => 'withdraw',
            'tx_id' => '0xabc220393',
            'sender_guid' => 123,
            'data' => [
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]
        ])->shouldBeCalled();

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setAddress('0xRequesterAddr')
            ->setGas(50);

        $this->request($request);
    }

    function it_should_complete_the_withdrawal_after_a_request(
        Pending $pending, 
        Transactions $transactions,
        Config $config,
        Ethereum $eth
    )
    {
        $this->beConstructedWith($pending, $transactions, $config, $eth);

        $pending->get('withdrawal', '0xabc220393')
            ->shouldBeCalled()
            ->willReturn([
                'tx_id' => '0xabc220393',
                'sender_guid' => 123,
                'data' => [
                    'address' => '0xRequesterAddr',
                    'amount' => 1000,
                    'gas' => 50,
                ],
            ]);

        $pending->delete('withdrawal', '0xabc220393')->shouldBeCalled();

        $user = new User();
        $user->guid = 123;
        $transactions->setUser($user)->shouldBeCalled()->willReturn($transactions);
        $transactions->setType('withdrawal')->shouldBeCalled()->willReturn($transactions);
        $transactions->setTx('0xabc220393')->shouldBeCalled()->willReturn($transactions);
        $transactions->setAmount(-1000)->shouldBeCalled()->willReturn($transactions);
        $transactions->create()->shouldBeCalled();

        $config->get('blockchain')->willReturn([
            'rewards_wallet_pkey' => 'private-key-here',
            'rewards_wallet_address' => '0xfunds-address',
            'withdraw_address' => '0xwidthdraw-address',
        ]);

        $eth->sendRawTransaction('private-key-here', [
            'from' => '0xfunds-address',
            'to' => '0xwidthdraw-address',
            'gasLimit' => Util::toHex(200000),
            'data' => '0xRESULT'
        ])->shouldBeCalled();

        $eth->encodeContractMethod('complete(address, uint256, uint256, uint256)', [
            '0xRequesterAddr',
            Util::toHex(123),
            Util::toHex(50),
            Util::toHex(1000)
        ])
        ->shouldBeCalled()
        ->willReturn('0xRESULT');

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(50);

        $this->complete($request);
    }

    function it_should_not_complete_the_withdrawal_if_user_mismatch(Pending $pending)
    {
        $this->beConstructedWith($pending);

        $pending->get('withdrawal', '0xabc220393')
            ->shouldBeCalled()
            ->willReturn([
                'tx_id' => '0xabc220393',
                'sender_guid' => 123,
                'data' => [
                    'address' => '0xRequesterAddr',
                    'amount' => 1000,
                    'gas' => 50,
                ],
            ]);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(1234)
            ->setAmount(1000)
            ->setGas(50);

        $this->shouldThrow('\Exception')->duringComplete($request);
    }

    function it_should_not_complete_the_withdrawal_if_address_mismatch(Pending $pending)
    {
        $this->beConstructedWith($pending);

        $pending->get('withdrawal', '0xabc220393')
            ->shouldBeCalled()
            ->willReturn([
                'tx_id' => '0xabc220393',
                'sender_guid' => 123,
                'data' => [
                    'address' => '0xRequesterAddr',
                    'amount' => 1000,
                    'gas' => 50,
                ],
            ]);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddrNOT')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(50);

        $this->shouldThrow('\Exception')->duringComplete($request);
    }

    function it_should_not_complete_the_withdrawal_if_amount_mismatch(Pending $pending)
    {
        $this->beConstructedWith($pending);

        $pending->get('withdrawal', '0xabc220393')
            ->shouldBeCalled()
            ->willReturn([
                'tx_id' => '0xabc220393',
                'sender_guid' => 123,
                'data' => [
                    'address' => '0xRequesterAddr',
                    'amount' => 1000,
                    'gas' => 50,
                ],
            ]);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(10001)
            ->setGas(50);

        $this->shouldThrow('\Exception')->duringComplete($request);
    }

    function it_should_not_complete_the_withdrawal_if_gas_mismatch(Pending $pending)
    {
        $this->beConstructedWith($pending);

        $pending->get('withdrawal', '0xabc220393')
            ->shouldBeCalled()
            ->willReturn([
                'tx_id' => '0xabc220393',
                'sender_guid' => 123,
                'data' => [
                    'address' => '0xRequesterAddr',
                    'amount' => 1000,
                    'gas' => 50,
                ],
            ]);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(501);

        $this->shouldThrow('\Exception')->duringComplete($request);
    }

}
