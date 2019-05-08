<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\Repository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\Report;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $cql;

    function let(Client $cql)
    {
        $this->beConstructedWith($cql);
        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_report(UserReport $report)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $values = $query['values'];

                return $values[0]->values()[0]->value() == 456
                    && $values[1]->values()[0]->value() == 'hash'
                    && $values[2] === 'urn:activity:123'
                    && $values[3]->value() == 2
                    && $values[4]->value() == 4
                    && $values[5]->time() == $ts;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $report->getReport()
            ->shouldBeCalled()
            ->willReturn((new Report)
                ->setEntityUrn("urn:activity:123")
                ->setTimestamp($ts));
        
        $report->getReporterGuid()
            ->shouldBeCalled()
            ->willReturn(456);
        
        $report->getReporterHash()
            ->shouldBeCalled()
            ->willReturn('hash');

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
