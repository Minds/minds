<?php

namespace Spec\Minds\Core\SMS\Services;

use Aws\Sns\SnsClient;
use Minds\Core\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SNSSpec extends ObjectBehavior
{
    function it_is_initializable(SnsClient $client)
    {
        $this->beConstructedWith($client, [
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1'
        ]);
        $this->shouldHaveType('Minds\Core\SMS\Services\SNS');
    }

    function it_should_return_true_if_SMS_was_sent(Config $config, SnsClient $client)
    {

        $client->publish(Argument::that(function ($args) {

            return $args['SenderID'] === 'Minds' && $args['SMSType'] === 'Transactional' && $args['Message'] === 'hello' && $args['PhoneNumber'] === '+44 1234';
        }))
            ->shouldBeCalled()
            ->willReturn(['MessageId' => 'test123']);

        $this->beConstructedWith($client, [
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1'
        ]);
        $this->send('+44 1234', 'hello')->shouldReturn(true);
    }

    function it_should_return_false_if_SMS_was_not_sent(Config $config, SnsClient $client)
    {

        $client->publish(Argument::that(function ($args) {

            return $args['SenderID'] === 'Minds' && $args['SMSType'] === 'Transactional' && $args['Message'] === 'hello' && $args['PhoneNumber'] === '+1 1234';
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->beConstructedWith($client, [
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1'
        ]);
        $this->send('+1 1234', 'hello')->shouldReturn(false);
    }

    function it_should_support_us_numbers_without_intl_code(Config $config, SnsClient $client)
    {

        $client->publish(Argument::that(function ($args) {

            return $args['SenderID'] === 'Minds' && $args['SMSType'] === 'Transactional' && $args['Message'] === 'hello' && $args['PhoneNumber'] === '+1234';
        }))
            ->shouldBeCalled()
            ->willReturn(['MessageId' => 'test123']);

        $this->beConstructedWith($client, [
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1'
        ]);
        $this->send('1234', 'hello')->shouldReturn(true);
    }

}
