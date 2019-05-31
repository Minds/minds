<?php

namespace Spec\Minds\Core\Analytics\Views;

use Minds\Core\Analytics\Views\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ViewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(View::class);
    }

    function it_should_set_client_meta()
    {
        $this
            ->setClientMeta([
                'page_token' => 'page_token_value',
                'position' => 'position_value',
                'platform' => 'platform_value',
                'source' => 'source_value',
                'medium' => 'medium_value',
                'campaign' => 'campaign_value',
                'delta' => 'delta_value',
            ])
            ->shouldReturn($this);

        $this
            ->getPageToken()
            ->shouldReturn('page_token_value');

        $this
            ->getPosition()
            ->shouldReturn('position_value');

        $this
            ->getPlatform()
            ->shouldReturn('platform_value');

        $this
            ->getSource()
            ->shouldReturn('source_value');

        $this
            ->getMedium()
            ->shouldReturn('medium_value');

        $this
            ->getCampaign()
            ->shouldReturn('campaign_value');

        $this
            ->getDelta()
            ->shouldReturn('delta_value');
    }
}
