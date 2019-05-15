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

    function it_should_add_a_report(UserReport $userReport)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $values = $query['values'];

                return $values[0]->values()[0]->value() == 456
                    && $values[2]->values()[0]->value() == 'hash'
                    && $values[3] === 'urn:activity:123'
                    && $values[4]->value() == 2
                    && $values[5]->value() == 4
                    && $values[6]->time() == $ts;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $userReport->getReport()
            ->shouldBeCalled()
            ->willReturn((new Report)
                ->setEntityUrn("urn:activity:123")
                ->setTimestamp($ts)
                ->setReasonCode(2)
                ->setSubReasonCode(4)
            );
        
        $userReport->getReporterGuid()
            ->shouldBeCalled()
            ->willReturn(456);
        
        $userReport->getReporterHash()
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->add($userReport)
            ->shouldBe(true);
    }

}
