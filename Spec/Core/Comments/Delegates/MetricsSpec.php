<?php

namespace Spec\Minds\Core\Comments\Delegates;

use Minds\Core\Analytics\Metrics\Event;
use Minds\Core\Comments\Comment;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Entity;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetricsSpec extends ObjectBehavior
{
    protected $metricsEvent;
    protected $entitiesBuilder;

    function let(
        Event $metricsEvent,
        EntitiesBuilder $entitiesBuilder
    )
    {
        $this->beConstructedWith($metricsEvent, $entitiesBuilder);

        $this->metricsEvent = $metricsEvent;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Delegates\Metrics');
    }

    function it_should_push(
        Comment $comment,
        User $owner,
        Entity $entity
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $comment->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $comment->getLuid()
            ->shouldBeCalled()
            ->willReturn('~PHPSPEC');

        $owner->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $owner->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn(5000);

        $entity->get('container_guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $entity->get('access_id')
            ->shouldBeCalled()
            ->willReturn(2);

        $entity->get('type')
            ->shouldBeCalled()
            ->willReturn('test');

        $entity->get('subtype')
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $entity->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->entitiesBuilder->single(1000)
            ->shouldBeCalled()
            ->willReturn($owner);

        $this->entitiesBuilder->single(5000)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->metricsEvent->setType('action')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setAction('comment')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setProduct('platform')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setUserGuid('1000')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setUserPhoneNumberHash('phpspec')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntityGuid('5000')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntityContainerGuid('1000')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntityAccessId(2)
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntityType('test')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntitySubtype('phpspec')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setEntityOwnerGuid('1000')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->setCommentGuid('~PHPSPEC')
            ->shouldBeCalled()
            ->willReturn($this->metricsEvent);

        $this->metricsEvent->push()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->push($comment)
            ->shouldNotThrow(\Exception::class);
    }
}
