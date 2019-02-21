<?php

namespace Spec\Minds\Core\Email;

use Minds\Core\Email\RouterHooks;
use PhpSpec\ObjectBehavior;
use Minds\Core\Analytics\Metrics\Event;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class RouterHooksSpec extends ObjectBehavior
{
    private $event;

    public function let(Event $event)
    {
        $this->beConstructedWith($event);
        $this->event = $event;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RouterHooks::class);
    }

    public function it_should_push_an_email_event_no_campaign(ServerRequest $request, Uri $uri)
    {
        $userGuid = '933120961241157645';
        $queryParams = [
            '__e_ct_guid' => $userGuid,
        ];

        $request->getUri()
            ->shouldBeCalled()
            ->willReturn($uri);

        $request->getQueryParams()
            ->shouldBeCalled()
            ->willReturn($queryParams);

        $this->event->setType('action')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setAction('email:clicks')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setProduct('platform')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setUserGuid($userGuid)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setPlatform('browser')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailCampaign('unknown')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailTopic('unknown')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailState('unknown')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->push()->shouldBeCalled();
        $this->withRouterRequest($request);
    }

    public function it_should_push_an_email_event(ServerRequest $request, Uri $uri)
    {
        $userGuid = '933120961241157645';
        $campaign = 'campaign';
        $topic = 'topic';
        $state = 'state';

        $queryParams = [
            '__e_ct_guid' => $userGuid,
            'campaign' => $campaign,
            'topic' => $topic,
            'state' => $state,
        ];

        $request->getUri()
            ->shouldBeCalled()
            ->willReturn($uri);

        $request->getQueryParams()
            ->shouldBeCalled()
            ->willReturn($queryParams);

        $this->event->setType('action')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setAction('email:clicks')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setProduct('platform')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setUserGuid($userGuid)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setPlatform('browser')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailCampaign($campaign)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailTopic($topic)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailState($state)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->push()->shouldBeCalled();
        $this->withRouterRequest($request);
    }

    public function it_should_push_an_email_unsubscribe_event(ServerRequest $request, Uri $uri)
    {
        $userGuid = '933120961241157645';
        $campaign = 'campaign';
        $topic = 'topic';
        $state = 'state';

        $queryParams = [
            '__e_ct_guid' => $userGuid,
            'campaign' => $campaign,
            'topic' => $topic,
            'state' => $state,
        ];

        $request->getUri()
            ->shouldBeCalled()
            ->willReturn($uri);

        $uri->getPath()->willReturn('/emails/unsubscribe');

        $request->getQueryParams()
            ->shouldBeCalled()
            ->willReturn($queryParams);

        $this->event->setType('action')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setAction('email:unsubscribe')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setProduct('platform')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setUserGuid($userGuid)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setPlatform('browser')
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailCampaign($campaign)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailTopic($topic)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->setEmailState($state)
            ->willReturn($this->event)
            ->shouldBeCalled();

        $this->event->push()->shouldBeCalled();
        $this->withRouterRequest($request);
    }
}
