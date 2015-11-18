<?php

namespace Spec\Minds\Core\Notification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Entities\User;
use Minds\Entities\Notification;
use Minds\Entities\Entity;

class FactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Notification\Factory');
    }
}
