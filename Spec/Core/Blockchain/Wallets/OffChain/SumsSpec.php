<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Data\Cassandra\Client;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SumsSpec extends ObjectBehavior
{
    /** @var Client */
    private $db;

    function let(Client $db)
    {
        $this->db = $db;

        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Sums');
    }

    function it_should_get_a_balance()
    {
        $this->db->request(Argument::any())
            ->willReturn([
                ['balance' => 12]
            ]);

        $this->getBalance()
            ->shouldReturn('12');
    }

    function it_shouldnt_find_a_balance()
    {
        $this->db->request(Argument::any())
            ->willReturn([]);

        $this->getBalance()
            ->shouldReturn(0);
    }

    function it_should_get_a_user_balance(User $user)
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT 
                SUM(amount) as balance 
                FROM blockchain_transactions_by_address
                WHERE user_guid = ?
                AND wallet_address = 'offchain'"
                && $built['values'][0]->value() === '123';
        }))
            ->willReturn([
                ['balance' => 12]
            ]);

        $this->setUser($user);

        $this->getBalance()
            ->shouldReturn('12');
    }

    function it_should_fail_getting_a_balance(User $user)
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT
                SUM(amount) as balance
                FROM blockchain_transactions_by_address
                WHERE user_guid = ?
                AND wallet_address = 'offchain'"
                && $built['values'][0]->value() === '123';
        }))
            ->willThrow(new \Exception());

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');
        $this->setUser($user);
        $this->getBalance()
            ->shouldReturn(0);
    }

    function it_should_get_a_contract_balance(User $user)
    {
        $user->get('guid')->willReturn(1000);

        $this->db->request(Argument::that(function ($prepared) {
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
                ['balance' => 12]
            ]);

        $this->setUser($user);

        $this
            ->getContractBalance('spec', false)
            ->shouldReturn('12');
    }

    function it_should_get_a_contract_balance_spend_only(User $user)
    {
        $user->get('guid')->willReturn(1000);

        $this->db->request(Argument::that(function ($prepared) {
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
                ['balance' => 12]
            ]);

        $this->setUser($user);

        $this
            ->getContractBalance('spec', true)
            ->shouldReturn('12');
    }

    function it_should_get_a_contract_balance_spend_only_and_timestamp(User $user)
    {
        $user->get('guid')->willReturn(1000);

        $this->db->request(Argument::that(function ($prepared) {
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
                ['balance' => 12]
            ]);

        $this->setUser($user);

        $this->setTimestamp(1000000);

        $this
            ->getContractBalance('spec', true)
            ->shouldReturn('12');
    }

    function it_should_fail_to_get_a_contract_balance(User $user)
    {
        $user->get('guid')->willReturn(1000);

        $this->db->request(Argument::that(function ($prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return $values[0]->value() == 1000 &&
                $values[1] == 'offchain' &&
                $values[2] == 'spec' &&
                strpos($cql, 'amount <') === false;
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->setUser($user);

        $this
            ->getContractBalance('spec', false)
            ->shouldReturn(0);
    }

    function it_shouldnt_find_a_contract_balance(User $user)
    {
        $user->get('guid')->willReturn(1000);

        $this->db->request(Argument::that(function ($prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return $values[0]->value() == 1000 &&
                $values[1] == 'offchain' &&
                $values[2] == 'spec' &&
                strpos($cql, 'amount <') === false;
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->setUser($user);

        $this
            ->getContractBalance('spec', false)
            ->shouldReturn(0);
    }

}
