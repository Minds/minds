<?php

namespace Messenger\Spec\Minds\Plugin\Messenger\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConversationsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Messenger\Core\Conversations');
    }
}
