<?php

namespace Spec\Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Payments\Plans\PaywallReview as PaywallReviewQueue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaywallReviewSpec extends ObjectBehavior
{
    /** @var PaywallReviewQueue */
    protected $paywallReview;

    function let(
        PaywallReviewQueue $paywallReview
    ) {
        $this->beConstructedWith($paywallReview);

        $this->paywallReview = $paywallReview;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Delegates\PaywallReview');
    }

    function it_should_queue(
        Blog $blog
    )
    {
        $blog->isMonetized()
            ->shouldBeCalled()
            ->willReturn(true);

        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->paywallReview->setEntityGuid(5000)
            ->shouldBeCalled()
            ->willReturn($this->paywallReview);

        $this->paywallReview->add()
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->queue($blog)
            ->shouldNotThrow();
    }

    function it_should_not_queue_if_not_monetized(
        Blog $blog
    )
    {
        $blog->isMonetized()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->paywallReview->add()
            ->shouldNotBeCalled();

        $this
            ->queue($blog)
            ->shouldNotThrow();
    }
}
