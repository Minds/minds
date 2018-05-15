<?php

namespace Spec\Minds\Core\Rewards;

use Minds\Core\Data\ElasticSearch\Client;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReferralValidatorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\ReferralValidator');
    }

    function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith($client);
    }

    function it_should_return_true_if_hash_wasnt_found(User $user)
    {
        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(
                [
                    'hits' => [
                        'hits' => []
                    ]
                ]
            );

        $this->setHash('hash');

        $this->validate()->shouldReturn(true);
    }

    function it_should_return_false_if_a_hash_was_found(User $user)
    {
        $this->client->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(
                [
                    'hits' => [
                        'hits' => [
                            1
                        ]
                    ]
                ]
            );

        $this->setHash('hash');

        $this->validate()->shouldReturn(false);
    }
}
