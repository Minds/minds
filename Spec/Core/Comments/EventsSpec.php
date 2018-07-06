<?php

namespace Spec\Minds\Core\Comments;

use Minds\Core\Di\Di;
use Minds\Core\Email\Mailer;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    /** @var Mailer */
    protected $mailer;

    function let(EventsDispatcher $dispatcher, Mailer $mailer)
    {
        Di::_()->bind('EventsDispatcher', function ($di) use ($dispatcher) {
            return $dispatcher->getWrappedObject();
        });

        Di::_()->bind('Mailer', function ($di) use ($mailer) {
            return $mailer->getWrappedObject();
        });

        $this->dispatcher = $dispatcher;
        $this->mailer = $mailer;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Events');
    }

    /*function it_should_register_the_ban_event()
    {
        $this->dispatcher->register('ban', 'user', Argument::any())
            ->shouldBeCalled();

        $this->register();
    }*/
}
