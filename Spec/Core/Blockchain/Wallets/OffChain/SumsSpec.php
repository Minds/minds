<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;

class SumsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Sums');
    }

    function it_sould_get_a_balance(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::any())
            ->willReturn([
                [ 'balance' => 12 ]
            ]);

        $this->getBalance()
            ->shouldReturn((double) 12);
    }

    function it_should_get_a_contract_balance(Client $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1000);

        $db->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return $values[0]->value() == 1000 &&
                $values[1] == 'offchain' &&
                $values[2] == 'spec' &&
                strpos($cql, 'amount <') === false;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'balance' => 12 ]
            ]);

        $this->setUser($user);

        $this
            ->getContractBalance('spec', false)
            ->shouldReturn((double) 12);
    }

    function it_should_get_a_contract_balance_spend_only(Client $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1000);

        $db->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return $values[0]->value() == 1000 &&
                $values[1] == 'offchain' &&
                $values[2] == 'spec' &&
                strpos($cql, 'amount <') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'balance' => 12 ]
            ]);

        $this->setUser($user);

        $this
            ->getContractBalance('spec', true)
            ->shouldReturn((double) 12);
    }

    function it_should_get_a_contract_balance_spend_only_and_timestamp(Client $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1000);

        $db->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return $values[0]->value() == 1000 &&
                $values[1] == 'offchain' &&
                $values[2]->time() == 1000000 &&
                $values[3] == 'spec' &&
                strpos($cql, 'amount <') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'balance' => 12 ]
            ]);

        $this->setUser($user);

        $this->setTimestamp(1000000);

        $this
            ->getContractBalance('spec', true)
            ->shouldReturn((double) 12);
    }

}
