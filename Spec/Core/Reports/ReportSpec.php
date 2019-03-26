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

    function it_should_return_the_most_popular_reason_code()
    {
        $this->setReports([
            (new UserReport)
                ->setReasonCode(2),
            (new UserReport)
                ->setReasonCode(2),
            (new UserReport)
                ->setReasonCode(3),
        ]);
        $this->getReasonCode()
            ->shouldBe(2);
    }

    function it_should_return_the_most_popular_sub_reason_code()
    {
        $this->setReports([
            (new UserReport)
                ->setSubReasonCode(2),
            (new UserReport)
                ->setSubReasonCode(2),
            (new UserReport)
                ->setSubReasonCode(1),
            (new UserReport)
                ->setSubReasonCode(1),
            (new UserReport)
                ->setSubReasonCode(1),
            (new UserReport)
                ->setSubReasonCode(3),
        ]);
        $this->getSubReasonCode()
            ->shouldBe(1);
    }
}
