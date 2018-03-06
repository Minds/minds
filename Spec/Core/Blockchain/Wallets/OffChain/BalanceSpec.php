<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Blockchain\Wallets\OffChain\Sums;
use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Sums as WithholdingSums;

class BalanceSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Balance');
    }

    function it_should_return_the_balance(Sums $sums)
    {
        $this->beConstructedWith($sums);

        $user = new User;
        $user->guid = 123;

        $sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($sums);
        $sums->getBalance()
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->get()->shouldReturn('50');
    }

    function it_should_return_the_available(Sums $sums, WithholdingSums $withholdingSums)
    {
        $this->beConstructedWith($sums, $withholdingSums);

        $user = new User;
        $user->guid = 123;

        $withholdingSums->setUserGuid($user)
            ->shouldBeCalled()
            ->willReturn($withholdingSums);

        $withholdingSums->get()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(10, 18));

        $sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->getBalance()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(50, 18));

        $this->setUser($user);
        $this->getAvailable()->shouldReturn((string) BigNumber::toPlain(40, 18));
    }

    function it_should_return_the_balance_by_contract(Sums $sums)
    {
        $this->beConstructedWith($sums);

        $user = new User;
        $user->guid = 123;

        $sums->setTimestamp(null)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->getContractBalance('spec', false)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec')->shouldReturn('50');
    }

    function it_should_return_the_balance_by_contract_with_timestamp(Sums $sums)
    {
        $this->beConstructedWith($sums);

        $user = new User;
        $user->guid = 123;

        $sums->setTimestamp(1000000)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->getContractBalance('spec', false)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec', 1000000)->shouldReturn('50');
    }


    function it_should_return_the_negative_balance_by_contract_with_timestamp(Sums $sums)
    {
        $this->beConstructedWith($sums);

        $user = new User;
        $user->guid = 123;

        $sums->setTimestamp(1000000)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->getContractBalance('spec', true)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec', 1000000, true)->shouldReturn('50');
    }


}
