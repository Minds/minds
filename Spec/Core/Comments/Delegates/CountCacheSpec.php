<?php

namespace Spec\Minds\Core\Comments\Delegates;

use Minds\Core\Comments\Comment;
use Minds\Core\Data\cache\abstractCacher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountCacheSpec extends ObjectBehavior
{
    /** @var abstractCacher */
    protected $cache;

    function let(
        abstractCacher $cache
    )
    {
        $this->beConstructedWith($cache);

        $this->cache = $cache;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Delegates\CountCache');
    }

    function it_should_destroy(
        Comment $comment
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->cache->destroy('comments:count:5000')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->destroy($comment)
            ->shouldNotThrow();
    }
}
