<?php

namespace Spec\Minds\Core\Entities\Actions;

use Minds\Core\Blogs\Blog;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Events\EventsDispatcher;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class SaveSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    function let(EventsDispatcher $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
        $this->dispatcher = $dispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Save::class);
    }

    function it_should_save_an_entity_using_its_save_method(User $user)
    {
        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);


        $this->setEntity($user);

        $this->save()->shouldReturn(true);
    }

    function it_should_saev_an_entity_via_the_entity_save_event(Blog $blog)
    {
        $this->dispatcher->trigger('entity:save', 'object:blog', ['entity' => $blog], false)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($blog);
        $this->save($blog)->shouldReturn(true);
    }
}
