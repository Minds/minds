<?php

namespace Spec\Minds\Core\Security\RateLimits\Aggregates;

use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Security\RateLimits\Aggregates\VotesDown;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VotesDownSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(VotesDown::class);
    }

    function it_should_return_downvotes_with_new_score(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::type('Minds\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entities' => [
                        'buckets' => [
                            ['key' => 123, 'doc_count' => 50, 'uniques' => ['value' => 50]],
                            ['key' => 456, 'doc_count' => 25, 'uniques' => ['value' => 25]]
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 50,
            456 => 25
        ]);
    }
}
