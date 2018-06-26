<?php

namespace Spec\Minds\Core\Analytics\Iterators;

use Minds\Core\Analytics\Iterators\EventsIterator;
use Minds\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    function let(Client $client)
    {
        $this->beConstructedWith($client, 'minds-metrics-*');
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventsIterator::class);
    }

    function it_should_get_the_list()
    {
        $this->client->request(Argument::type('Minds\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entity_guid.keyword' => [
                        'buckets' => [
                            [
                                'key' => 1529581013443,
                            ],
                            [
                                'key' => 1529581013689,
                            ],
                        ]
                    ]
                ]
            ]);

        $this->setPeriod(strtotime('-1 day'));
        $this->setTerms(['entity_guid.keyword']);

        $this->next();
        $this->current()->shouldReturn(1529581013443);
        $this->next();
        $this->current()->shouldReturn(1529581013689);

    }
}
