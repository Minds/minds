<?php

namespace Spec\Minds\Core\Blockchain\Pledges;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;
use Cassandra\Timestamp;
use Cassandra\Varint;
use Minds\Core\Blockchain\Pledges\Pledge;
use Spec\Minds\Mocks;

class RepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Pledges\Repository');
    }

    function it_should_add_a_pledge(Client $db)
    {
        $this->beConstructedWith($db);

        $db->batchRequest(Argument::that(function($requests) {
            return $requests[0]['values'][0] == 'hash'
                && $requests[0]['values'][1] == new Varint(123)
                && $requests[0]['values'][2] == '0xWALLETADDR'
                && $requests[0]['values'][3] == new Timestamp(time())
                && $requests[0]['values'][4] == new Varint(50);
            }), 1)
            ->shouldBeCalled();

        $pledge = new Pledge;
        $pledge
            ->setPhoneNumberHash('hash')
            ->setUserGuid(123)
            ->setWalletAddress('0xWALLETADDR')
            ->setTimestamp(time()) 
            ->setAmount(50);

        $this->add($pledge);
    }

    function it_should_get_a_list_of_pledges(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function($pledges) {
            return true;
        }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'phone_number_hash' => 'hash',
                    'user_guid' => 123,
                    'wallet_address' => '0xWALLETADDR',
                    'timestamp' => new Timestamp(time()),
                    'amount' => 50,        
                ]
            ], ''));

        $result = $this->getList([
            'phone_number_hash' => 'hash'
        ]);
  
        $result['pledges'][0]
            ->getPhoneNumberHash()
            ->shouldBe('hash');

        $result['pledges'][0]
            ->getUserGuid()
            ->shouldBe(123);
        
        $result['pledges'][0]
            ->getWalletAddress()
            ->shouldBe('0xWALLETADDR');

        $result['pledges'][0]
            ->getAmount()
            ->shouldBe('50');
    }

    function it_should_get_a_single_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function($query) {
                $values = $query->build()['values'];
                return $values[0] == 'hash';
            }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'phone_number_hash' => 'hash',
                    'user_guid' => 123,
                    'wallet_address' => '0xWALLETADDR',
                    'timestamp' => new Timestamp(time()),
                    'tx' => '0xtid',
                    'contract' => 'spec',
                    'amount' => 50,                    
                    'completed' => true,
                    'data' => json_encode([ 'foo' => 'bar' ])
                ]
            ], ''));

        $result = $this->get('hash');
        
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
            ->getAmount()
            ->shouldBe('50');
    }

}
