<?php

namespace Spec\Minds\Common;

use Minds\Common\Access;
use PhpSpec\ObjectBehavior;

class AccessSpec extends ObjectBehavior
{
    public function it_should_return_string_for_an_access_id()
    {
        $this::idToString(Access::UNLISTED)->shouldBe('Unlisted');
    }

    public function it_should_return_unknown_for_invalid_access_id()
    {
        $this::idToString(666)->shouldBe('Unknown');
    }
}
