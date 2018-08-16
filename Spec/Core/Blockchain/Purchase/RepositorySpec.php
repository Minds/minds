<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use Cassandra\Timestamp;
use Cassandra\Varint;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks;

class RepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Purchase\Repository');
    }

    function it_should_add_a_purchase(Client $db)
    {
        $this->beConstructedWith($db);

        $db->batchRequest(Argument::that(function ($requests) {
            return $requests[0]['values'][0] == 'hash'
                && $requests[0]['values'][1] == 'txHash'
                && $requests[0]['values'][2] == new Varint(123)
                && $requests[0]['values'][3] == '0xWALLETADDR'
                && $requests[0]['values'][4] == new Timestamp(time())
                && $requests[0]['values'][5] == new Varint(50)
                && $requests[0]['values'][6] == new Varint(1);
        }), 1)
            ->shouldBeCalled();

        $purchase = new Purchase;
        $purchase
            ->setPhoneNumberHash('hash')
            ->setTx('txHash')
            ->setUserGuid(123)
            ->setWalletAddress('0xWALLETADDR')
            ->setTimestamp(time())
            ->setRequestedAmount(50)
            ->setIssuedAmount(1);

        $this->add($purchase);
    }

    function it_should_get_a_list_of_purchases(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT * from token_purchases WHERE phone_number_hash = ? AND user_guid = ? AND wallet_address = ? ALLOW FILTERING"
                && $built['values'][0] === 'hash'
                && $built['values'][1]->value() === '123'
                && $built['values'][2] === '0xWALLET';
        }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'phone_number_hash' => 'hash',
                    'tx' => 'txHash',
                    'user_guid' => new Varint(123),
                    'wallet_address' => '0xWALLETADDR',
                    'timestamp' => new Timestamp(time()),
                    'requested_amount' => new Varint(50),
                    'issued_amount' => new Varint(1),
                    'status' => 'approved',
                ]
            ], ''));

        $result = $this->getList([
            'phone_number_hash' => 'hash',
            'user_guid' => '123',
            'wallet_address' => '0xWALLET',
        ]);

        $result['purchases'][0]
            ->getPhoneNumberHash()
            ->shouldBe('hash');

        $result['purchases'][0]
            ->getTx()
            ->shouldBe('txHash');

        $result['purchases'][0]
            ->getUserGuid()
            ->shouldBe(123);

        $result['purchases'][0]
            ->getWalletAddress()
            ->shouldBe('0xWALLETADDR');

        $result['purchases'][0]
            ->getRequestedAmount()
            ->shouldBe('50');

        $result['purchases'][0]
            ->getIssuedAmount()
            ->shouldBe('1');

        $result['purchases'][0]
            ->getUnIssuedAmount()
            ->toInt()
            ->shouldBe(49);
    }

    function it_should_fail_to_get_a_list_of_purchases(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT * from token_purchases WHERE phone_number_hash = ? AND user_guid = ? AND wallet_address = ? ALLOW FILTERING"
                && $built['values'][0] === 'hash'
                && $built['values'][1]->value() === '123'
                && $built['values'][2] === '0xWALLET';
        }))
            ->willThrow(new \Exception());

        $this->getList([
            'phone_number_hash' => 'hash',
            'user_guid' => '123',
            'wallet_address' => '0xWALLET',
        ])
            ->shouldReturn([]);
    }

    function it_shouldnt_find_any_purchases(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT * from token_purchases WHERE phone_number_hash = ? AND user_guid = ? AND wallet_address = ? ALLOW FILTERING"
                && $built['values'][0] === 'hash'
                && $built['values'][1]->value() === '123'
                && $built['values'][2] === '0xWALLET';
        }))
            ->willReturn([]);

        $this->getList([
            'phone_number_hash' => 'hash',
            'user_guid' => '123',
            'wallet_address' => '0xWALLET',
        ])
            ->shouldReturn([]);
    }

    function it_should_get_a_single_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $values = $query->build()['values'];
            return $values[0] == 'hash';
        }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'phone_number_hash' => 'hash',
                    'user_guid' => new Varint(123),
                    'wallet_address' => '0xWALLETADDR',
                    'timestamp' => new Timestamp(time()),
                    'tx' => '0xtid',
                    'contract' => 'spec',
                    'requested_amount' => new Varint(50),
                    'issued_amount' => new Varint(49),
                    'completed' => true,
                    'data' => json_encode(['foo' => 'bar']),
                    'status' => 'approved',
                ]
            ], ''));

        $result = $this->get('hash', 'txHash');

        $result
            ->getPhoneNumberHash()
            ->shouldBe('hash');

        $result
            ->getUserGuid()
            ->shouldBe(123);

        $result
            ->getWalletAddress()
            ->shouldBe('0xWALLETADDR');

        $result
            ->getRequestedAmount()
            ->shouldBe('50');
    }

    function it_should_fail_to_get_a_single_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $values = $query->build()['values'];
            return $values[0] == 'hash';
        }))
            ->willThrow(new \Exception());

        $this->get('hash', 'txHash')
            ->shouldReturn(null);
    }

    function it_shouldnt_find_a_single_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $values = $query->build()['values'];
            return $values[0] == 'hash';
        }))
            ->willReturn([]);

        $this->get('hash', 'txHash')
            ->shouldReturn(null);
    }

    function it_should_delete_a_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "DELETE FROM blockchain_transactions where phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete('hash')->shouldReturn(true);
    }

    function it_should_fail_to_delete_a_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "DELETE FROM blockchain_transactions where phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->delete('hash')->shouldReturn(false);
    }
}
