<?php

namespace Spec\Minds\Core\Analytics\Metrics;

use Minds\Core\Data\cache\apcu;
use Minds\Core\Data\Call;
use Minds\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActiveSpec extends ObjectBehavior
{
    /** @var Call */
    protected $db;
    /** @var Client */
    protected $client;
    /** @var apcu */
    protected $cacher;

    public function let(Call $db, Client $client, apcu $cacher)
    {
        $this->beConstructedWith($db, $client, $cacher);
        $this->db = $db;
        $this->client = $client;
        $this->cacher = $cacher;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Metrics\Active');
    }

    public function it_should_get_the_analytics()
    {
        $this->client->request(Argument::type('Minds\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'counts' => [
                        'buckets' => [
                            [
                                'key' => 1529581013443,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50
                                ]
                            ],
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldHaveCount(1);
        $this->get()->shouldReturn([
            [
                'timestamp' => 1529581013443 / 1000,
                'date' => date('d-m-Y', 1529581013443 / 1000),
                'total' => 50
            ]
        ]);
    }
}
