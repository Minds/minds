<?php

namespace Spec\Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Entities\Actions\Save;
use Minds\Entities\Activity;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateActivitySpec extends ObjectBehavior
{
    /** @var Save */
    protected $saveAction;

    function let(
        Save $saveAction
    ) {
        $this->beConstructedWith($saveAction);

        $this->saveAction = $saveAction;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Delegates\CreateActivity');
    }

    function it_should_save(
        Blog $blog,
        User $user
    )
    {
        $blog->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn($user);

        $blog->getTitle()
            ->shouldBeCalled()
            ->willReturn('blog title');

        $blog->getBody()
            ->shouldBeCalled()
            ->willReturn('<p>blog body</p>');

        $blog->getUrl()
            ->shouldBeCalled()
            ->willReturn('http://phpspec/blog/5000');

        $blog->getIconUrl()
            ->shouldBeCalled()
            ->willReturn('http://phpspec/icon.spec.ext');

        $blog->isMature()
            ->shouldBeCalled()
            ->willReturn(false);

        $user->export()
            ->shouldBeCalled()
            ->willReturn([]);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->saveAction->setEntity(Argument::type(Activity::class))
            ->shouldBeCalled()
            ->willReturn($this->saveAction);

        $this->saveAction->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->save($blog)
            ->shouldNotThrow();

    }
}
