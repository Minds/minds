<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DenormalizedEntitySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\DenormalizedEntity');
    }

    public function it_returns_a_guid()
    {
        $this->getGuid()->shouldBeNumeric();
    }

    public function it_can_set_the_rowKey()
    {
        $this->setRowKey('specs')->shouldReturn($this);
        $this->getRowKey()->shouldReturn('specs');
    }

    public function it_should_export()
    {
        $this->export()->shouldBeArray();
    }
}
