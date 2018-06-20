<?php

namespace Spec\Minds\Core\Blogs;

use Minds\Core\Blogs\Blog;
use Minds\Core\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HeaderSpec extends ObjectBehavior
{
    protected $config;

    function let(
        Config $config
    )
    {
        $this->beConstructedWith($config);

        $this->config = $config;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Header');
    }

    function it_should_resolve_to_existing_bg(Blog $blog)
    {
        $this->config->get('cdn_url')
            ->shouldBeCalled()
            ->willReturn('http://phpspec/');

        $blog->hasHeaderBg()
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $blog->getLastUpdated()
            ->shouldBeCalled()
            ->willReturn(123456789);

        $this
            ->resolve($blog, 128)
            ->shouldReturn('http://phpspec/fs/v1/banners/5000/123456789');
    }

    function it_should_resolve_to_first_img_on_body(Blog $blog)
    {
        $this->config->get('cdn_url')
            ->shouldBeCalled()
            ->willReturn('http://phpspec/');

        $blog->hasHeaderBg()
            ->shouldBeCalled()
            ->willReturn(false);

        $blog->getBody()
            ->shouldBeCalled()
            ->willReturn('<p><img src="/image.spec.ext"></p>');

        $blog->getLastUpdated()
            ->shouldBeCalled()
            ->willReturn(123456789);

        $encodedImgSrc = urlencode('/image.spec.ext');

        $this
            ->resolve($blog, 128)
            ->shouldReturn("http://phpspec/api/v2/media/proxy?size=128&src={$encodedImgSrc}&_=123456789");
    }

    // TODO: Find a way to mock ElggFile
    // TODO: Write write() and read() tests
}
