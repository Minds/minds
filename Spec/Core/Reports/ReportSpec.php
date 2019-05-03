<?php

namespace Spec\Minds\Core\Reports;

use Minds\Core\Reports\Report;
use Minds\Core\Reports\UserReports\UserReport;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Report::class);
    }

    function it_should_return_a_urn()
    {
        $this->setEntityUrn('urn:activity:123')
            ->setReasonCode(2)
            ->setSubReasonCode(1)
            ->setTimestamp(1556898915000);

        $this->getUrn()
            ->shouldBe('urn:report:(urn:activity:123)-2-1-1556898915000');
    }

}
