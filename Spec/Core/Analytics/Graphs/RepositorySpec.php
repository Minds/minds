<?php

namespace Spec\Minds\Core\Analytics\Graphs;

use Minds\Core\Analytics\Graphs\Repository;
use Minds\Core\Analytics\Graphs\Graph;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $client;

    function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_list_of_graphs()
    {
        $this->client->request(Argument::that(function($prepared) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'key' => 'avgpageviews-mau_unique-month',
                    'last_synced' => 15000,
                    'data' => '{ "key": "value" }',
                ],
                [
                    'key' => 'avgpageviews-mau_unique-year',
                    'last_synced' => 15000,
                    'data' => '{ "key": "value" }',
                ],
                [
                    'key' => 'avgpageviews-mau_unique-day',
                    'last_synced' => 15000,
                    'data' => '{ "key": "value" }',
                ],
            ]);
        $graphs = $this->getList();
        $graphs->shouldHaveCount(3);

        $graphs[0]->getKey()
            ->shouldBe('avgpageviews-mau_unique-month');
        $graphs[0]->getData()
            ->shouldReturn([
                'key' => 'value'
            ]);
    }

    function it_should_return_a_single_graph()
    {
        $this->client->request(Argument::that(function($prepared) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'key' => 'avgpageviews-mau_unique-month',
                    'last_synced' => 15000,
                    'data' => '{ "key": "value" }',
                ]
            ]);
        $graph = $this->get('urn:graph:avgpageviews-mau_unique-month');

        $graph->getKey()
            ->shouldBe('avgpageviews-mau_unique-month');
        $graph->getData()
            ->shouldReturn([
                'key' => 'value'
            ]);
    }

    function it_should_add_a_graph()
    {
        $graph = new Graph();
        $graph->setKey('avgpageviews-mau_unique-month')
            ->setLastSynced(strtotime('midnight'))
            ->setData([
                'key' => 'value',
            ]);
        $this->client->request(Argument::that(function($prepared) {
            $values = $prepared->build()['values'];
            return $values[0] === 'avgpageviews-mau_unique-month'
                && $values[1]->time() == strtotime('midnight')
                && $values[2] === '{"key":"value"}';
        }))
            ->shouldBeCalled();
        $this->add($graph)
            ->shouldBe(true);
    }

}
