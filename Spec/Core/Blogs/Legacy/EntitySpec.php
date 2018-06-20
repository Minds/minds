<?php

namespace Spec\Minds\Core\Blogs\Legacy;

use Minds\Core\Blogs\Blog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blogs\Legacy\Entity');
    }

    function it_should_build()
    {
        $this
            ->build([
                'type' => '',
                'subtype' => '',
                'guid' => '',
                'owner_guid' => '',
                'container_guid' => '',
                'access_id' => '',
                'title' => '',
                'description' => '',
                'excerpt' => '',
                'slug' => '',
                'perma_url' => '',
                'header_bg' => '',
                'header_top' => '',
                'time_created' => '',
                'time_updated' => '',
                'last_updated' => '',
                'status' => '',
                'published' => '',
                'monetized' => '',
                'license' => '',
                'time_published' => '',
                'categories' => '[]',
                'custom_meta' => '{}',
                'rating' => '',
                'draft_access_id' => '',
                'last_save' => '',
                'wire_threshold' => '{}',
                'paywall' => '',
                'mature' => '',
                'spam' => '',
                'deleted' => '',
                'boost_rejection_reason' => '',
                'ownerObj' => '{}',
            ])
            ->shouldReturnAnInstanceOf(Blog::class);
    }
}
