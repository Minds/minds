<?php

namespace Spec\Minds\Core\Wire;

use Minds\Core\Config;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Cassandra;

class SumsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Wire\Sums');
    }

    function it_should_return_the_sum_sent(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::that(function($query) {
                $values = $query->build()['values'];
                return $values[0] == new Cassandra\Varint('123') && $values[2] == new Cassandra\Timestamp(time());
            }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'amount_sum' => new \Cassandra\Decimal(0),
                    'wei_sum' => new \Cassandra\Varint(10)
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $this->setSender('123')
            ->setFrom(time());

        $this->getSent()->shouldReturn('10');
    }

    function it_should_return_the_sum_sent_to_receiver(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::that(function($query) {
                $values = $query->build()['values'];
                return $values[0] == new Cassandra\Varint('123') && $values[1] == new Cassandra\Varint('456');
            }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'amount_sum' => new \Cassandra\Decimal(0),
                    'wei_sum' => new \Cassandra\Varint(20)
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $this->setSender('123')
            ->setReceiver('456')
            ->setFrom(time());

        $this->getSent()->shouldReturn('20');
    }

    function it_should_get_a_sum_by_receiver(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'amount_sum' => new \Cassandra\Decimal(0),
                    'wei_sum' => new \Cassandra\Varint(10)
                ]
            ]);

        $this->beConstructedWith($cassandra, $config);

        $this->setReceiver(1000)->getReceived()->shouldReturn('10');
    }

    function it_should_get_a_sum_by_entity(Client $cassandra, Config $config)
    {

        $cassandra->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))->willReturn([
            [
                'amount_sum' => new \Cassandra\Decimal(0),
                'wei_sum' => new \Cassandra\Varint(10)
            ]
        ]);

        $this->beConstructedWith($cassandra, $config);

        $this->setEntity(5000)->getEntity()->shouldReturn('10');
    }

}
