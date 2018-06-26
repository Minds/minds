<?php

namespace Spec\Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SlugSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Delegates\Slug');
    }

    function it_should_generate(
        Blog $blog
    )
    {
        $blog->getPermaUrl()
            ->shouldBeCalled()
            ->willReturn(false);

        $blog->isPublished()
            // ->shouldBeCalled()
            ->willReturn(false);

        $blog->getTitle()
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $blog->getSlug()
            ->shouldBeCalled()
            ->willReturn('');

        $blog->setSlug('phpspec')
            ->shouldBeCalled()
            ->willReturn($blog);

        $blog->isDirty('slug')
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->getUrl()
            ->shouldBeCalled()
            ->willReturn('http://phpspec/blog/1');

        $blog->setPermaUrl('http://phpspec/blog/1')
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->generate($blog)
            ->shouldNotThrow();
    }

    function it_should_not_generate_if_permaurl_and_published(
        Blog $blog
    )
    {
        $blog->getPermaUrl()
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->isPublished()
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->setSlug(Argument::any())
            ->shouldNotBeCalled();

        $blog->isDirty('slug')
            ->shouldBeCalled()
            ->willReturn(false);

        $blog->setPermaUrl(Argument::any())
            ->shouldNotBeCalled();

        $this
            ->generate($blog)
            ->shouldNotThrow();
    }
}
