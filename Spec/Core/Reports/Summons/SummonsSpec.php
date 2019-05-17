<?php

namespace Spec\Minds\Core\Reports\Summons;

use Exception;
use Minds\Core\Reports\Summons\Summons;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SummonsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Summons::class);
    }

    function it_should_set_valid_status()
    {
        $this
            ->shouldNotThrow()
            ->duringSetStatus('awaiting');

        $this
            ->shouldNotThrow()
            ->duringSetStatus('accepted');

        $this
            ->shouldNotThrow()
            ->duringSetStatus('declined');

        $this
            ->shouldThrow(new Exception('Invalid status'))
            ->duringSetStatus(null);

        $this
            ->shouldThrow(new Exception('Invalid status'))
            ->duringSetStatus('phpspec:invalidstatus');
    }

    function it_should_return_bool_if_awaiting()
    {
        $this
            ->isAwaiting()
            ->shouldReturn(false);

        $this->setStatus('awaiting');

        $this
            ->isAwaiting()
            ->shouldReturn(true);
    }

    function it_should_return_bool_if_accepted()
    {
        $this
            ->isAccepted()
            ->shouldReturn(false);

        $this->setStatus('accepted');

        $this
            ->isAccepted()
            ->shouldReturn(true);
    }

    function it_should_return_bool_if_declined()
    {
        $this
            ->isDeclined()
            ->shouldReturn(false);

        $this->setStatus('declined');

        $this
            ->isDeclined()
            ->shouldReturn(true);
    }
}
