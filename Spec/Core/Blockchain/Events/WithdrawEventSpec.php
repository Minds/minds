<?php

namespace Spec\Minds\Core\Blockchain\Events;

use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Withdraw\Manager;
use Minds\Core\Blockchain\Transactions\Transaction;

class WithdrawEventSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Events\WithdrawEvent');
    }

    function it_should_complete_withdrawal_on_event(Manager $manager)
    {
        $this->beConstructedWith($manager);

        $manager->complete(Argument::that(function($request) {
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
            'data' => $data,
            'transactionHash' => '0x62a70ccf3b37b9368efa3dd4785e715139c994ba9957a125e299b14a8eccd00c'
        ], new Transaction);
    }

}
