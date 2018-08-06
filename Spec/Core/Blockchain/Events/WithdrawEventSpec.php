<?php

namespace Spec\Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config\Config;
use Minds\Core\Rewards\Withdraw\Manager;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WithdrawEventSpec extends ObjectBehavior
{
    /** @var Manager */
    protected $manager;
    /** @var Repository */
    protected $txRepo;
    /** @var Config */
    protected $config;

    function let(Manager $manager, Repository $txRepo, Config $config)
    {
        $this->beConstructedWith($manager, $txRepo, $config);

        $this->manager = $manager;
        $this->txRepo = $txRepo;
        $this->config = $config;

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'withdraw' => [
                        'contract_address' => '0xasd'
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Events\WithdrawEvent');
    }

    function it_should_get_topics()
    {
        $this->getTopics()->shouldReturn([
            '0x317c0f5ab60805d3e3fb6aaa61ccb77253bbb20deccbbe49c544de4baa4d7f8f',
            'blockchain:fail'
        ]);
    }

    function it_should_complete_withdrawal_on_event()
    {
        $this->manager->complete(Argument::that(function ($request) {
            return $request->getTx() == '0x62a70ccf3b37b9368efa3dd4785e715139c994ba9957a125e299b14a8eccd00c'
                && $request->getAddress() == '0x177fd9efd24535e73b81e99e7f838cdef265e6cb'
                && $request->getUserGuid() == (string) BigNumber::_('786645648014315523')
                && $request->getGas() == (string) BigNumber::_('67839000000000')
                && $request->getAmount() == (string) BigNumber::_('10000000000000000000');
        }), Argument::type('\Minds\Core\Blockchain\Transactions\Transaction'))
            ->shouldBeCalled();

        $data = "0x000000000000000000000000177fd9efd24535e73b81e99e7f838cdef265e6cb"
            . "0000000000000000000000000000000000000000000000000aeaba0c8e001003"
            . "00000000000000000000000000000000000000000000000000003db2ff7f3600"
            . "0000000000000000000000000000000000000000000000008ac7230489e80000";
        $this->onRequest([
            'address' => '0xasd',
            'data' => $data,
            'transactionHash' => '0x62a70ccf3b37b9368efa3dd4785e715139c994ba9957a125e299b14a8eccd00c'
        ], new Transaction);
    }

    function it_should_send_a_blockchain_fail_event(Transaction $transaction)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('withdraw');

        $transaction->setFailed(true)
            ->shouldBeCalled();

        $this->txRepo->update($transaction, ['failed'])
            ->shouldBeCalled();

        $this->event('blockchain:fail', ['address' => '0xasd'], $transaction);
    }

    function it_should_fail_if_the_transaction_address_isnt_the_same_as_the_contract_address(Transaction $transaction)
    {
        $this->shouldThrow(new \Exception("Event does not match address"))->during('event',
            ['blockchain:fail', ['address' => '0x123'], $transaction]);
    }

    function it_should_send_a_blockchain_fail_event_but_it_isnt_the_same_contract(Transaction $transaction)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('wire');

        $this->shouldThrow(new \Exception("Failed but not a withdrawal"))->during('event',
            ['blockchain:fail', ['address' => '0xasd'], $transaction]);
    }

    function it_should_abort_if_not_from_address(Manager $manager, Repository $txRepo, Config $config)
    {
        $this->beConstructedWith($manager, $txRepo, $config);

        $config->get('blockchain')->willReturn([
            'contracts' => [
                'withdraw' => [
                    'contract_address' => '0x277fd9efd24535e73b81e99e7f838cdef265e6cb',
                ]
            ]
        ]);

        $data = "0x000000000000000000000000177fd9efd24535e73b81e99e7f838cdef265e6cb"
            . "0000000000000000000000000000000000000000000000000aeaba0c8e001003"
            . "00000000000000000000000000000000000000000000000000003db2ff7f3600"
            . "0000000000000000000000000000000000000000000000008ac7230489e80000";
        $this->shouldThrow(new \Exception('Incorrect address sent the withdraw event'))
            ->duringOnRequest([
                'address' => '0x177fd9efd24535e73b81e99e7f838cdef265e6cb',
                'data' => $data,
                'transactionHash' => '0x62a70ccf3b37b9368efa3dd4785e715139c994ba9957a125e299b14a8eccd00c'
            ], (new Transaction())->setContract('withdraw'));

    }
}
