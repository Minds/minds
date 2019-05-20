<?php

namespace Spec\Minds\Core\Reports\Appeals\Delegates;

use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Core\Queue\Runners\ReportsAppealSummon;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Appeals\Delegates\SummonDelegate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SummonDelegateSpec extends ObjectBehavior
{
    /** @var QueueClient */
    protected $queue;

    function let(
        QueueClient $queue
    )
    {
        $this->beConstructedWith($queue);
        $this->queue = $queue;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SummonDelegate::class);
    }

    function it_should_queue_on_appeal(Appeal $appeal)
    {
        $this->queue->setQueue(ReportsAppealSummon::class)
            ->shouldBeCalled()
            ->willReturn($this->queue);

        $this->queue->send([
            'appeal' => $appeal,
            'cohort' => null,
        ])
            ->shouldBeCalled();

        $this
            ->shouldNotThrow()
            ->duringOnAppeal($appeal);
    }
}
