<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Blockchain\Wallets\OffChain\Sums;
use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Sums as WithholdingSums;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class BalanceSpec extends ObjectBehavior
{
    /** @var Sums */
    private $sums;
    /** @var WithholdingSums */
    private $withholdingSums;

    function let(Sums $sums, WithholdingSums $withholdingSums)
    {
        $this->sums = $sums;
        $this->withholdingSums = $withholdingSums;

        $this->beConstructedWith($sums, $withholdingSums);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Balance');
    }

    function it_should_return_the_balance()
    {
        $user = new User;
        $user->guid = 123;

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);
        $this->sums->getBalance()
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->get()->shouldReturn('50');
    }

    function it_should_return_the_available()
    {
        $user = new User;
        $user->guid = 123;

        $this->withholdingSums->setUserGuid($user)
            ->shouldBeCalled()
            ->willReturn($this->withholdingSums);

        $this->withholdingSums->get()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(10, 18));

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->getBalance()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(50, 18));

        $this->setUser($user);
        $this->getAvailable()->shouldReturn((string) BigNumber::toPlain(40, 18));
    }

    function it_should_return_0_when_getting_available()
    {
        $user = new User;
        $user->guid = 123;

        $this->withholdingSums->setUserGuid($user)
            ->shouldBeCalled()
            ->willReturn($this->withholdingSums);

        $this->withholdingSums->get()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(50, 18));

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->getBalance()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(10, 18));

        $this->setUser($user);
        $this->getAvailable()->shouldReturn('0');
    }

    function it_should_return_the_balance_by_contract()
    {
        $user = new User;
        $user->guid = 123;

        $this->sums->setTimestamp(null)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->getContractBalance('spec', false)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec')->shouldReturn('50');
    }

    function it_should_return_the_balance_by_contract_with_timestamp()
    {
        $user = new User;
        $user->guid = 123;

        $this->sums->setTimestamp(1000000)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->getContractBalance('spec', false)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec', 1000000)->shouldReturn('50');
    }


    function it_should_return_the_negative_balance_by_contract_with_timestamp()
    {
        $user = new User;
        $user->guid = 123;

        $this->sums->setTimestamp(1000000)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->sums);

        $this->sums->getContractBalance('spec', true)
            ->shouldBeCalled()
            ->willReturn('50');

        $this->setUser($user);
        $this->getByContract('spec', 1000000, true)->shouldReturn('50');
    }

}
