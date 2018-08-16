<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OnChain;

use Minds\Core\Blockchain\Token;
use Minds\Core\Blockchain\Wallets\OnChain\Balance;
use Minds\Core\Data\cache\Redis;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class BalanceSpec extends ObjectBehavior
{
    private $token;
    private $cache;

    function let(Token $token, Redis $cache)
    {
        $this->token = $token;
        $this->cache = $cache;

        $this->beConstructedWith($token, $cache);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Balance::class);
    }

    function it_should_get_the_balance_from_cache(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->cache->get('blockchain:balance:0x123')
            ->shouldBeCalled()
            ->willReturn(serialize(10 ** 18));

        $this->setUser($user);
        $this->get()->shouldReturn(10 ** 18);
    }

    function it_should_get_the_balance_from_database(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->cache->get('blockchain:balance:0x123')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->token->balanceOf('0x123')
            ->shouldBeCalled()
            ->willReturn(10 ** 18);

        $this->cache->set('blockchain:balance:0x123', serialize(10 ** 18), 60)
            ->shouldBeCalled();

        $this->setUser($user);
        $this->get()->shouldReturn(10 ** 18);
    }

    function it_shouldnt_get_the_balance_if_the_user_has_no_wallet_set(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('');

        $this->setUser($user);
        $this->get()->shouldReturn(0);
    }
}
