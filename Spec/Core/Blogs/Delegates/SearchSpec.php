<?php

namespace Spec\Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Blogs\Delegates\Search;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SearchSpec extends ObjectBehavior
{
    /**
     * @var EventsDispatcher
     */
    protected $eventsDispatcher;

    function let(
        EventsDispatcher $eventsDispatcher
    )
    {
        $this->beConstructedWith($eventsDispatcher);
        $this->eventsDispatcher = $eventsDispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Search::class);
    }

    function it_should_react_to_index(Blog $blog)
    {
        $this->eventsDispatcher->trigger('search:index', 'object:blog', [
            'entity' => $blog,
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->shouldNotThrow(\Exception::class)
            ->duringIndex($blog);
    }

    function it_should_react_to_prune(Blog $blog)
    {
        $this->eventsDispatcher->trigger('search:cleanup', 'object:blog', [
            'entity' => $blog,
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->shouldNotThrow(\Exception::class)
            ->duringPrune($blog);
    }
}
