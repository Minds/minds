<?php

namespace Spec\Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Verdict\Repository;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class RepositorySpec extends ObjectBehavior
{
    private $es;

    function let(Client $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_verdict(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@initial_jury_decided_timestamp'] === $ts
                    && $doc['initial_jury_action'] === 'explicit'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_a_verdict_as_overturned(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@initial_jury_decided_timestamp'] === $ts
                    && $doc['initial_jury_action'] === 'overturned'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@appeal_jury_decided_timestamp'] === $ts
                    && $doc['appeal_jury_action'] === 'explicit'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict_as_overturned(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@appeal_jury_decided_timestamp'] === $ts
                    && $doc['appeal_jury_action'] === 'overturned'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

}
