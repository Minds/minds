<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Blockchain\Wallets\OffChain\Balance;
use Minds\Core\Config;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CapSpec extends ObjectBehavior
{
    /** @var Balance */
    protected $offchainBalance;

    function let(
        Config $config,
        Balance $offchainBalance
    ) {
        $this->offchainBalance = $offchainBalance;

        $config->get('blockchain')->willReturn([
            'offchain' => [
                'cap' => 7,
            ],
        ]);

        $this->beConstructedWith($config, $offchainBalance);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Cap');
    }

    function it_should_return_allowance(
        User $user
    ) {
        $today = strtotime('today 00:00');

        $this->offchainBalance->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->offchainBalance);

        $this->offchainBalance->getByContract('offchain:test', $today, true)
            ->shouldBeCalled()
            ->willReturn(BigNumber::toPlain(-5, 18));

        $this
            ->setUser($user)
            ->setContract('test')
            ->allowance()
            ->shouldReturn((string) BigNumber::toPlain(2, 18));
    }

    function it_should_check_if_allowed(
        User $user
    ) {
        $today = strtotime('today 00:00');

        $this->offchainBalance->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->offchainBalance);

        $this->offchainBalance->getByContract('offchain:test', $today, true)
            ->shouldBeCalled()
            ->willReturn(BigNumber::toPlain(-5, 18));

        $this
            ->setUser($user)
            ->setContract('test')
            ->isAllowed((string) BigNumber::toPlain(2, 18))
            ->shouldReturn(true);
    }

    function it_should_check_if_not_allowed(
        User $user
    ) {
        $today = strtotime('today 00:00');

        $this->offchainBalance->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->offchainBalance);

        $this->offchainBalance->getByContract('offchain:test', $today, true)
            ->shouldBeCalled()
            ->willReturn(BigNumber::toPlain(-5, 18));

        $this
            ->setUser($user)
            ->setContract('test')
            ->isAllowed((string) BigNumber::toPlain(3, 18))
            ->shouldReturn(false);
    }
}
