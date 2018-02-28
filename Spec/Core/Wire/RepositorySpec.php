<?php

namespace Spec\Minds\Core\Wire;

use Minds\Core\Config;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Core\Wire\Wire;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks;
use Cassandra;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Wire\Repository');
    }

    function it_should_get_a_list_of_wires(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn(new Mocks\Cassandra\Rows([
                [
                    'sender_guid' => 123,
                    'receiver_guid' => 1234,
                    'timestamp' => '2017-05-03',
                    'entity_guid' => 1337,
                    'recurring' => true,
                    'amount' => null,
                    'wei' => 10,
                    'method' => 'tokens',
                    'active' => true
                ]
            ], 1));

        $this->beConstructedWith($cassandra, $config);

        $wire = new Wire();
        $wire->setSender(new User(123))
            ->setReceiver(new User(1234))
            ->setTimestamp('2017-05-03')
            ->setEntity(Entities::get(['guid' => 1337])[0])
            ->setRecurring(true)
            ->setAmount('10')
            ->setMethod('tokens');

        $result = $this->getList([
            'receiver_guid' => 123
        ]);

        $result['wires'][0]->shouldBeAnInstanceOf('Minds\Core\Wire\Wire');
        $result['wires'][0]->shouldBeLike($wire);
    }

    function it_should_use_send_table_if_sender_guid(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::that(function($request) {
                return true;
                $query = $request->build();
                return strpos($query['string'], "SELECT * FROM wire_by_sender", 0) !== FALSE;
            }))
            ->shouldBeCalled();

        $this->beConstructedWith($cassandra, $config);

        $result = $this->getList([
            'sender_guid' => 123
        ]);
    }

    function it_should_add_a_wire(Client $cassandra, Config $config)
    {
        $cassandra->batchRequest(Argument::that(function($requests) {
            return true;
            return $requests[0]['values'][0] == new Cassandra\Varint(1234)
                && $requests[0]['values'][1] == new Cassandra\Varint(123)
                && $requests[0]['values'][2] == 'tokens'
                && $requests[0]['values'][3] == new Cassandra\Timestamp(time());
            }), Cassandra::BATCH_UNLOGGED)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->beConstructedWith($cassandra, $config);

        //$activity->guid = 1337;

        $wire = new Wire();
        $wire->setGuid(987)
            ->setSender((object)['guid' => 123])
            ->setReceiver((object)['guid' => 1234])
            ->setTimestamp(time())
            ->setEntity((object)['guid' => 133])
            ->setRecurring(true)
            ->setAmount(10)
            ->setMethod('tokens');

        $this->add($wire)
            ->shouldReturn(true);
    }

}
