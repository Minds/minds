<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Boost\Events;
use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    function let(EventsDispatcher $dispatcher)
    {
        Di::_()->bind('EventsDispatcher', function ($di) use ($dispatcher) {
            return $dispatcher->getWrappedObject();
        });

        $this->dispatcher = $dispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Events::class);
    }

    function it_should_register()
    {
        $this->dispatcher->register('boost:completed', 'boost', Argument::any())
            ->shouldBeCalled();

        $this->register();
    }
}
