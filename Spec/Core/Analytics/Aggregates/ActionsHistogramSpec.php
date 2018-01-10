<?php

namespace Spec\Minds\Core\Analytics\Aggregates;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\ElasticSearch\Client;

class ActionsHistogramSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Aggregates\ActionsHistogram');
    }

    function it_should_should_get_aggregates(Client $client)
    {
        $this->beConstructedWith($client);
        
        $client->request(Argument::type('Minds\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'counts' => [
                        'buckets' => [
                            [ 
                                'key' => 123,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50
                                ]
                            ],
                            [ 
                                'key' => 456,
                                'doc_count' => 25,
                                'uniques' => [
                                    'value' => 20
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 50,
            456 => 20
        ]);
    }

}
