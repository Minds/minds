<?php

namespace Spec\Minds\Core\Analytics\Graphs;

use Minds\Core\Analytics\Graphs\Manager;
use Minds\Core\Analytics\Graphs\Repository;
use Minds\Core\Analytics\Graphs\Graph;
use Minds\Core\Analytics\Graphs\Mappings;
use Minds\Core\Analytics\Graphs\Aggregates\AvgPageviews;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $mappings;

    function let(Repository $repository, Mappings $mappings)
    {
        $this->beConstructedWith($repository, $mappings);
        $this->repository = $repository;
        $this->mappings = $mappings;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_a_graph_by_urn()
    {
        $graph = new Graph();

        $this->repository->get('urn:graph:avg-mau')
            ->shouldBeCalled()
            ->willReturn($graph);

        $this->get('urn:graph:avg-mau')
            ->shouldReturn($graph);
    }

    function it_should_add_a_graph()
    {
        $graph = new Graph();

        $this->repository->add($graph)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($graph)
            ->shouldReturn(true);
    }

    function it_should_sync_aggregates_to_graphs(AvgPageviews $avgPageviewsAggregate)
    {
        $avgPageviewsAggregate->fetch(Argument::any())
            ->shouldBeCalled()
            ->willReturn(12);
    
        $graph = new Graph();
        $graph->setKey('avgpageviews-mau_unique-month')
            ->setLastSynced(time())
            ->setData(12);

        $this->repository->add($graph)
            ->shouldBeCalled();

        $this->mappings->getMapping('avgpageviews')
            ->shouldBeCalled()
            ->willReturn($avgPageviewsAggregate);

        $this->sync([
            'aggregate' => 'avgpageviews',
            'key' => 'mau_unique',
        ]);
    }

}
