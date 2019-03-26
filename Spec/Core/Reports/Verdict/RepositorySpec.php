<?php

namespace Spec\Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Verdict\Repository;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Repository as ReportsRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class RepositorySpec extends ObjectBehavior
{
    private $es;
    private $reportsRepository;

    function let(Client $es, ReportsRepository $reportsRepository)
    {
        $this->beConstructedWith($es, $reportsRepository);
        $this->es = $es;
        $this->reportsRepository = $reportsRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_verdict(Verdict $verdict)
    {
        $ts = (int) microtime(true);
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
        $ts = (int) microtime(true);
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
        $ts = (int) microtime(true);
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
        $ts = (int) microtime(true);
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

    function it_should_get_a_single_verdict()
    {
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                (new Report())
                    ->setEntityGuid(123)
                    ->setInitialJuryDecisions([
                        (new Decision)
                            ->setJurorGuid(4),
                    ]),
            ]);


        $verdict = $this->get(123);

        $verdict->getReport()->getEntityGuid()
            ->shouldBe(123);
        $verdict->isAppeal()
            ->shouldBe(false);

        $decisions = $verdict->getDecisions();
        $decisions[0]->getJurorGuid()
                    ->shouldBe(4);
    }

    function it_should_get_a_single_verdict_as_an_appeal()
    {
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                (new Report())
                    ->setAppeal(true)
                    ->setEntityGuid(123)
                    ->setAppealJuryDecisions([
                        (new Decision)
                            ->setJurorGuid(4),
                    ]),
            ]);
        
        $verdict = $this->get(123);

        $verdict->getReport()->getEntityGuid()
            ->shouldBe(123);
        $verdict->isAppeal()
            ->shouldBe(true);
        $decisions = $verdict->getDecisions();
        $decisions[0]->getJurorGuid()
                    ->shouldBe(4);
    }

}
