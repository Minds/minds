<?php

namespace Spec\Minds\Core\Analytics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\ElasticSearch\Client;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Manager');
    }

    function it_should_return_a_batch_of_analytics(
        Client $es
    ) {
        $this->beConstructedWith($es);

        $es->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'counts' => [
                        'buckets' => [
                            [
                                "key_as_string" => "2017-12-29T00:00:00.000Z",
                                "key" => 1514505600000,
                                "doc_count" => 71,
                                "uniques" => [
                                    "value" => 62
                                ]
                            ],
                            [
                                "key_as_string" => "2017-11-26T00:00:00.000Z",
                                "key" => 1511654400000,
                                "doc_count" => 102,
                                "uniques" => [
                                    "value" => 102
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $this->setTo(strtotime('1 week ago') * 1000);
        $this->setFrom(time() * 1000);
        $this->setInterval('day');

        $this->getCounts()->shouldReturn([
            1514505600000 => [
                'subscribers' => 62,
                'comments' => 62,
                'reminds' => 62,
                'votes' => 62,
                'referrals' => 62,
            ],
            1511654400000 => [
                'subscribers' => 102,
                'comments' => 102,
                'reminds' => 102,
                'votes' => 102,
                'referrals' => 102,
            ]
        ]);
    }

    function it_should_return_a_batch_of_top_analytics(
        Client $es
    ) {
        $this->beConstructedWith($es);

        $es->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'counts' => [
                        'buckets' => [
                            [
                                "key" => 1234,
                                "doc_count" => 71,
                                "uniques" => [
                                    "value" => 71
                                ]
                            ],
                            [
                                "key" => 5678,
                                "doc_count" => 87,
                                "uniques" => [
                                    "value" => 87
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        $this->setFrom(strtotime('1 week ago') * 1000);
        $this->setTo(time() * 1000);
        $this->setMetric('vote:up');

        $this->getTopCounts()->shouldReturn([
            [
                'user_guid' => 1234,
                'value' => 71
            ],
            [
                'user_guid' => 5678,
                'value' => 87
            ],
        ]);
    }

}
