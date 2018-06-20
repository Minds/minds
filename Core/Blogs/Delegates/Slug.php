<?php

/**
 * Minds Blogs Slug
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;

class Slug
{
    public function generate(Blog $blog)
    {
        if (!$blog->getPermaUrl() || !$blog->isPublished()) {
            if ($blog->getTitle() && !$blog->getSlug()) {
                $blog->setSlug($blog->getTitle());
            }
        }

        if ($blog->isDirty('slug')) {
            $blog->setPermaUrl($blog->getUrl());
        }
    }
}
