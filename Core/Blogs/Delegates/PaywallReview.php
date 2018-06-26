<?php

/**
 * Minds Blog Paywall Review Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Payments\Plans\PaywallReview as PaywallReviewQueue;

class PaywallReview
{
    /** @var PaywallReviewQueue */
    protected $paywallReview;

    /**
     * PaywallReview constructor.
     * @param null $paywallReview
     */
    public function __construct($paywallReview = null)
    {
        $this->paywallReview = $paywallReview ?: new PaywallReviewQueue();
    }

    /**
     * Queues a blog
     * @param Blog $blog
     */
    public function queue(Blog $blog)
    {
        if ($blog->isMonetized()) {
            $this->paywallReview
                ->setEntityGuid($blog->getGuid())
                ->add();
        }
    }
}
