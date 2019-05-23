<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\ElasticRepository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class ElasticRepositorySpec extends ObjectBehavior
{
    private $es;

    function let(Client $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ElasticRepository::class);
    }

    function it_should_add_a_report(UserReport $report)
    {
        $ts = (int) microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['report'];
                return $params[0]['reporter_guid'] === 456
                    && $params[0]['reason'] === 2
                    && $params[0]['sub_reason'] === 4
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report->getReport()
            ->shouldBeCalled()
            ->willReturn((new Report)
                ->setEntityGuid(123));
        
        $report->getReporterGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $report->getReasonCode()
            ->shouldBeCalled()
            ->willReturn(2);

        $report->getSubReasonCode()
            ->shouldBeCalled()
            ->willReturn(4);

        $this->add($report)
            ->shouldBe(true);
    }

}
