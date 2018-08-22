<?php

namespace Spec\Minds\Core\Rewards\Withdraw;

use Minds\Core\Blockchain\Wallets\OffChain\Balance;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Pending;
use Minds\Core\Rewards\Withdraw\Request;
use Minds\Core\Rewards\Withdraw\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Blockchain\Util;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Transactions\Manager as BlockchainTx;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config\Config;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Withdraw\Manager');
    }

    function it_should_allow_a_withdrawal_request_to_be_made(
        BlockchainTx $offChainTransactions,
        Repository $repository,
        Balance $offChainBalance
    )
    {
        $this->beConstructedWith($offChainTransactions, null, null, null, $repository, $offChainBalance);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setWalletAddress('0xRequesterAddr')
            ->setAmount(1000)
            ->setTimestamp(time())
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);

        $offChainTransactions->add($transaction)->shouldBeCalled();

        $offChainBalance->setUser(Argument::type(User::class))
            ->shouldBeCalled()
            ->willReturn($offChainBalance);

        $offChainBalance->getAvailable()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(10, 18));

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setAddress('0xRequesterAddr')
            ->setTimestamp(time())
            ->setGas(50);

        $this->request($request);
    }

    function it_should_not_allow_a_withdrawl_request_to_be_made_if_already_exists_in_last_24_hours(
        BlockchainTx $offChainTransactions,
        Repository $repository
    )
    {
        $this->beConstructedWith($offChainTransactions, null, null, null, $repository);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setAddress('0xRequesterAddr')
            ->setTimestamp(time())
            ->setGas(50);

        $repository->getList([
                'user_guid' => 123,
                'contract' => 'withdraw',
                'from' => strtotime('-1 day')
            ])
            ->willReturn([
                'withdraws' => [ $request ]
            ]);

        $this->shouldThrow('\Exception')->duringRequest($request);
    }

    function it_should_complete_the_withdrawal_after_a_request(
        BlockchainTx $txManager, 
        Transactions $offChainTransactions,
        Config $config,
        Ethereum $eth,
        Repository $repository
    )
    {
        $this->beConstructedWith($txManager, $offChainTransactions, $config, $eth, $repository);

        $user = new User();
        $user->guid = 123;
        $offChainTransactions->setUser($user)->shouldBeCalled()->willReturn($offChainTransactions);
        $offChainTransactions->setType('withdraw')->shouldBeCalled()->willReturn($offChainTransactions);
        //$offChainTransactions->setTx('0xabc220393')->shouldBeCalled()->willReturn($offChainTransactions);
        $offChainTransactions->setAmount(-1000)->shouldBeCalled()->willReturn($offChainTransactions);
        $offChainTransactions->create()->shouldBeCalled();
        $config->get('blockchain')->willReturn([
            'contracts' => [
                'withdraw' => [
                    'contract_address' => '0xwidthdraw-address',
                    'wallet_pkey' => 'private-key-here',
                    'wallet_address' => '0xfunds-address',
                ]
            ]
        ]);

        $eth->sendRawTransaction('private-key-here', [
            'from' => '0xfunds-address',
            'to' => '0xwidthdraw-address',
            'gasLimit' => BigNumber::_(4612388)->toHex(true),
            'gasPrice' => BigNumber::_(10000000000)->toHex(true),
            'data' => '0xRESULT'
        ])->shouldBeCalled()
        ->willReturn('0xRESULTRawTransaction');

        $eth->encodeContractMethod('complete(address,uint256,uint256,uint256)', [
            '0xRequesterAddr',
            BigNumber::_(123)->toHex(true),
            BigNumber::_(50)->toHex(true),
            BigNumber::_(1000)->toHex(true)
        ])
        ->shouldBeCalled()
        ->willReturn('0xRESULT');

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(50);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);
        $addRequest = $request;
        $addRequest->setCompletedTx('0xRESULTRawTransaction');

        $repository->add($addRequest)->shouldBeCalled();

        $this->complete($request, $transaction);
    }

    function it_should_not_complete_the_withdrawal_if_user_mismatch(BlockchainTx $txManager)
    {
        $this->beConstructedWith($txManager);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(1234)
            ->setAmount(1000)
            ->setGas(50);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);

        $this->shouldThrow('\Exception')->duringComplete($request, $transaction);
    }

    function it_should_not_complete_the_withdrawal_if_address_mismatch(BlockchainTx $txManager)
    {
        $this->beConstructedWith($txManager);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddrNOT')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(50);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);

        $this->shouldThrow('\Exception')->duringComplete($request, $transaction);
    }

    function it_should_not_complete_the_withdrawal_if_amount_mismatch(BlockchainTx $txManager)
    {
        $this->beConstructedWith($txManager);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(10001)
            ->setGas(50);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);

        $this->shouldThrow('\Exception')->duringComplete($request, $transaction);
    }

    function it_should_not_complete_the_withdrawal_if_gas_mismatch(BlockchainTx $txManager)
    {
        $this->beConstructedWith($txManager);

        $request = new Request();
        $request->setTx('0xabc220393')
            ->setAddress('0xRequesterAddr')
            ->setUserGuid(123)
            ->setAmount(1000)
            ->setGas(501);

        $transaction = new Transaction();
        $transaction
            ->setContract('withdraw')
            ->setTx('0xabc220393')
            ->setUserGuid(123)
            ->setData([
                'amount' => 1000,
                'gas' => 50,
                'address' => '0xRequesterAddr'
            ]);

        $this->shouldThrow('\Exception')->duringComplete($request, $transaction);
    }

}
