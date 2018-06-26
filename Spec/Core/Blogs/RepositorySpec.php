<?php

namespace Spec\Minds\Core\Blogs;

use Minds\Common\Repository\Response;
use Minds\Core\Blogs\Blog;
use Minds\Core\Blogs\Legacy;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    /** @var Legacy\Repository */
    protected $legacyRepository;

    function let(
        Legacy\Repository $legacyRepository
    )
    {
        $this->beConstructedWith($legacyRepository);

        $this->legacyRepository = $legacyRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Repository');
    }

    function it_should_get_list(Response $response)
    {
        $opts = [ 'limit' => 123 ];

        $this->legacyRepository->getList($opts)
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getList($opts)
            ->shouldReturn($response);
    }

    function it_should_get(Blog $blog)
    {
        $this->legacyRepository->get(5000)
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->get(5000)
            ->shouldReturn($blog);
    }

    function it_should_add(Blog $blog)
    {
        $attributes = [ 'attribute1', 'attribute2' ];

        $this->legacyRepository->add($blog, $attributes)
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->setEphemeral(false)
            ->shouldBeCalled()
            ->willReturn($blog);

        $blog->markAllAsPristine()
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->add($blog, $attributes)
            ->shouldReturn(true);
    }

    function it_should_update(Blog $blog)
    {
        $this->legacyRepository->update($blog)
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->setEphemeral(false)
            ->shouldBeCalled()
            ->willReturn($blog);

        $blog->markAllAsPristine()
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->update($blog)
            ->shouldReturn(true);
    }

    function it_should_delete(Blog $blog)
    {
        $this->legacyRepository->delete($blog)
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->setEphemeral(true)
            ->shouldBeCalled()
            ->willReturn($blog);

        $this
            ->delete($blog)
            ->shouldReturn(true);
    }
}
