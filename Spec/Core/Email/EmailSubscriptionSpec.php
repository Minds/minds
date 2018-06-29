<?php

namespace Spec\Minds\Core\Email;

use Minds\Core\Email\EmailSubscription;
use PhpSpec\ObjectBehavior;

class EmailSubscriptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EmailSubscription::class);
    }

    function it_should_load_from_an_array()
    {
        $this->beConstructedWith([
            'userGuid' => '123',
            'campaign' => 'with',
            'topic' => 'top_posts',
            'value' => true
        ]);

        $this->getUserGuid()->shouldReturn('123');
        $this->getCampaign()->shouldReturn('with');
        $this->getTopic()->shouldReturn('top_posts');
        $this->getValue()->shouldReturn(true);
    }

    function it_should_export_the_entity()
    {
        $this->beConstructedWith([
            'userGuid' => '123',
            'campaign' => 'with',
            'topic' => 'top_posts',
            'value' => true
        ]);

        $this->export()->shouldReturn(
            [
                'campaign' => 'with',
                'topic' => 'top_posts',
                'user_guid' => '123',
                'value' => true
            ]
        );
    }
}
