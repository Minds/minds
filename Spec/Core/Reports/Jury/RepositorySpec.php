<?php

namespace Spec\Minds\Core\Reports\Jury;

use Minds\Core\Reports\Jury\Repository;
use Minds\Core\Reports\Jury\Decision;
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

    function it_should_add_initial_jury_decision(Decision $decision)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['action'] === 'explicit'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'ctx._source.initial_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_initial_jury_decision_as_overturned(Decision $decision)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['action'] === 'overturned'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'ctx._source.initial_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
    
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_appeal_jury_decision(Decision $decision)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['action'] === 'explicit'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'ctx._source.appeal_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_appeal_jury_decision_as_overturned(Decision $decision)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['action'] === 'overturned'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'ctx._source.appeal_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($decision)
            ->shouldBe(true);
    }
}
