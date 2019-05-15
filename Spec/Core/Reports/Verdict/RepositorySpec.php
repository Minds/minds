<?php

namespace Spec\Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Verdict\Repository;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Repository as ReportsRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\Cassandra\Client;

class RepositorySpec extends ObjectBehavior
{
    private $cql;
    private $reportsRepository;

    function let(Client $cql, ReportsRepository $reportsRepository)
    {
        $this->beConstructedWith($cql, $reportsRepository);
        $this->cql = $cql;
        $this->reportsRepository = $reportsRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_verdict(Verdict $verdict)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) {
                $query = $prepared->build();
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report = new Report();
        $report->setEntityUrn('urn:activity:123');

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->isUpheld()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_a_verdict_as_overturned(Verdict $verdict)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->isUpheld()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict(Verdict $verdict)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->isUpheld()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict_as_overturned(Verdict $verdict)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->isUpheld()
            ->willReturn(false);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    /*function it_should_get_a_single_verdict()
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
    }*/

}
