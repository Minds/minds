<?php

namespace Spec\Minds\Core\Blockchain\Transactions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;
use Cassandra\Timestamp;
use Cassandra\Varint;
use Minds\Core\Blockchain\Transactions\Transaction;
use Spec\Minds\Mocks;

class RepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Transactions\Repository');
    }

    function it_should_add_transactions(Client $db)
    {
        $this->beConstructedWith($db);

        $db->batchRequest(Argument::that(function($requests) {
            return $requests[0]['values'][0] == "spec"
                && $requests[0]['values'][1] == '0xtid'
                && $requests[0]['values'][2] == new Varint(123)
                && $requests[0]['values'][3] == new Timestamp(time())
                && $requests[0]['values'][4] == false
                && $requests[0]['values'][5] == json_encode([ 'foo' => 'bar']);
            }), 1)
            ->shouldBeCalled();

        $transaction = new Transaction;
        $transaction->setContract('spec')
            ->setTx('0xtid')
            ->setUserGuid(123)
            ->setTimestamp(time())
            ->setData([
                'foo' => 'bar'
            ]);

        $this->add($transaction);
    }

    function it_should_get_a_list_of_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function($transactions) {
            return true;
        }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'contract' => 'spec',
                    'tx' => '0xtid',
                    'user_guid' => 123,
                    'timestamp' => time(),
                    'completed' => true,
                    'data' => json_encode([ 'foo' => 'bar' ])
                ]
            ], ''));

        $result = $this->getList([
            'contract' => 'spec',
            'user_guid' => 123
            ]);
  
        $result['transactions'][0]
            ->getContract('spec')
            ->shouldBe('spec');
        
        $result['transactions'][0]
            ->getUserGuid()
            ->shouldBe(123);
        
        $result['transactions'][0]
            ->getTx('0xtid')
            ->shouldBe('0xtid');
        
        $result['transactions'][0]
            ->isCompleted()
            ->shouldBe(true);
        
        $result['transactions'][0]
            ->getData()
            ->shouldBe([ 'foo' => 'bar' ]);
    }

    function it_should_get_a_single_transaction(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::that(function($query) {
                $values = $query->build()['values'];
                return $values[0] == '0xtid';
            }))
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'contract' => 'spec',
                    'tx' => '0xtid',
                    'user_guid' => 123,
                    'timestamp' => new Timestamp(),
                    'completed' => true,
                    'data' => json_encode([ 'foo' => 'bar' ])
                ]
            ], ''));

        $result = $this->get('0xtid');
        
        $result
            ->getUserGuid()
            ->shouldBe(123);
        
        $result
            ->getTx()
            ->shouldBe('0xtid');
        
        //$result
        //    ->getTimestamp()
        //    ->shouldBe(time());

        $result
            ->isCompleted()
            ->shouldBe(true);
        
        $result
            ->getData()
            ->shouldBe([ 'foo' => 'bar' ]);
    }

}
