<?php

namespace Spec\Minds\Core\Security\AbuseGuard;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared;

class AggregatesSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\AbuseGuard\Aggregates');
    }

    function it_should_set_period()
    {
        $this->setPeriod('now-5m', 'now-2m')->shouldReturn($this);
    }

    function it_should_set_limit()
    {
        $this->setLimit(100)->shouldReturn($this);
    }

    function it_should_get_down_votes(Client $client)
    {
        $this->beConstructedWith($client);

        $result = [
            'aggregations' => [
                'vote:down' => [
                    'buckets' => [
                        [ 'key' => 'oob', 'doc_count' => 2 ],
                        [ 'key' => 'foo', 'doc_count' => 5 ],
                        [ 'key' => 'too', 'doc_count' => 1 ]
                    ]
                ]
            ]
        ];

        $client->request(Argument::type('Minds\Core\Data\ElasticSearch\Prepared\Search'))->willReturn($result);
        
        $this->getDownVotes()->shouldHaveCount(3);
        $this->getDownVotes()->shouldContain([ 'guid' => 'oob', 'count' => 2 ]);
        $this->getDownVotes()->shouldContain([ 'guid' => 'foo', 'count' => 5 ]);
        $this->getDownVotes()->shouldContain([ 'guid' => 'too', 'count' => 1 ]);
    }

    function it_should_get_comments(Client $client)
    {
        $this->beConstructedWith($client);

        $result = [
            'aggregations' => [
                'comments' => [
                    'buckets' => [
                        [ 'key' => 'coo', 'doc_count' => 2 ],
                        [ 'key' => 'cob', 'doc_count' => 5 ],
                        [ 'key' => 'coa', 'doc_count' => 1 ]
                    ]
                ]
            ]
        ];

        $client->request(Argument::type('Minds\Core\Data\ElasticSearch\Prepared\Search'))->willReturn($result);
        
        $this->getComments()->shouldHaveCount(3);
        $this->getComments()->shouldContain([ 'guid' => 'coo', 'count' => 2 ]);
        $this->getComments()->shouldContain([ 'guid' => 'cob', 'count' => 5 ]);
        $this->getComments()->shouldContain([ 'guid' => 'coa', 'count' => 1 ]);
    }

}
