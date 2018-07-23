<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Transactions\Manager as TxManager;
use Minds\Core\Blockchain\Purchase\Repository;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Purchase\Manager');
    }

    function it_should_return_the_users_purchases(Repository $repo, TxManager $txManager)
    {
        $this->beConstructedWith($repo, $txManager);

        $repo->get('hash')
            ->shouldBeCalled()
            ->willReturn(
                (new Purchase)
                    ->setIssuedAmount(5)
                    ->setRequestedAmount(10)
            );

        $user = new User();
        $user->phone_number_hash = 'hash';
        $this->getPurchasedAmount($user)->shouldBe("10");
    }

}
