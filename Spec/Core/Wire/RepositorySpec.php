<?php

namespace Spec\Minds\Core\Wire;

use Minds\Core\Config;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Entities;
use Minds\Entities\Activity;
use Minds\Entities\User;
use Minds\Entities\Wire;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Wire\Repository');
    }

    function it_should_get_a_sum_by_sender(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'amount_sum' => new \Cassandra\Decimal(10)
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $this->getSumBySender(123, 'points')->shouldReturn('10');
    }

    function it_should_get_a_sum_by_receiver(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'amount_sum' => new \Cassandra\Decimal(10)
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $this->getSumByReceiver(123, 'points')->shouldReturn('10');
    }

    function it_should_get_a_sum_by_entity(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))->willReturn([
            [
                'amount_sum' => new \Cassandra\Decimal(10)
            ]
        ]);

        $this->beConstructedWith($cassandra, $config);

        $this->getSumByEntity(123, 'points')->shouldReturn('10');
    }

    function it_should_get_a_wire(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'sender_guid' => 123,
                    'receiver_guid' => 1234,
                    'timestamp' => '2017-05-03',
                    'entity_guid' => 1337,
                    'recurring' => true,
                    'amount' => 10,
                    'method' => 'points',
                    'active' => true
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $wire = new Wire();
        $wire->setFrom(new User(123))
            ->setTo(new User(1234))
            ->setTimeCreated('2017-05-03')
            ->setEntity(Entities::get(['guid' => 1337])[0])
            ->setRecurring(true)
            ->setAmount(10)
            ->setMethod('points')
            ->setActive(true);

        $this->get(1337, 1234, 'points')[0]->shouldBeAnInstanceOf('Minds\Entities\Wire');
        $this->get(1337, 1234, 'points')[0]->shouldBeLike($wire);
    }

    function it_should_add_a_wire(Client $cassandra, Config $config) {
        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->beConstructedWith($cassandra, $config);

        //$activity->guid = 1337;

        $wire = new Wire();
        $wire->setGuid(987)
            ->setFrom((object) [ 'guid' => 123])
            ->setTo((object) [ 'guid' => 1234])
            ->setTimeCreated(time())
            ->setEntity((object) [ 'guid' => 133])
            ->setRecurring(true)
            ->setAmount(10)
            ->setMethod('points')
            ->setActive(true);

        $this->add($wire)
            ->shouldReturn(true);
    }
}