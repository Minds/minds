<?php

namespace Spec\Minds\Core\Hashtags;

use Minds\Core\Hashtags\HashtagEntity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HashtagEntitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HashtagEntity::class);
    }

    function it_should_have_guid_getters_and_setters()
    {
        $this->setGuid(100)
            ->shouldBeAnInstanceOf($this);

        $this->getGuid()->shouldReturn(100);
    }

    function it_should_have_hashtag_getters_and_setters()
    {
        $this->setHashtag('hashtag1')
            ->shouldBeAnInstanceOf($this);

        $this->getHashtag()->shouldReturn('hashtag1');
    }
}
