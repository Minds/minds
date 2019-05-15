<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Reports\Verdict\Delegates\ActionDelegate;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Actions;
use Minds\Core\Reports\Strikes\Manager as StrikesManager;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionDelegateSpec extends ObjectBehavior
{
    private $entitiesBuilder;
    private $actions;
    private $strikesManager;

    function let(
        EntitiesBuilder $entitiesBuilder,
        Actions $actions,
        StrikesManager $strikesManager
    )
    {
        $this->beConstructedWith($entitiesBuilder, $actions, null, $strikesManager);
        $this->entitiesBuilder = $entitiesBuilder;
        $this->actions = $actions;
        $this->strikesManager = $strikesManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ActionDelegate::class);
    }

    function it_should_apply_nsfw_flags(Entity $entity)
    {
        $report = new Report;
        $report->setEntityUrn('urn:activity:123')
            ->setReasonCode(2)
            ->setSubReasonCode(1);

        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setUphold(true);

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $entity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([ 2 ]);

        $entity->setNsfw([ 1, 2 ])
            ->shouldBeCalled();

        $entity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([ ]);

        $entity->setNsfwLock([ 1 ])
            ->shouldBeCalled();

        $entity->save()
            ->shouldBeCalled();

        $this->strikesManager->countStrikesInTimeWindow(Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn(0);

        $this->strikesManager->add(Argument::any())
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_removed_if_illegal(Entity $entity, Entity $user)
    {
        $report = new Report;
        $report->setEntityUrn('urn:activity:123')
            ->setEntityOwnerGuid(456)
            ->setReasonCode(1)
            ->setSubReasonCode(1);

        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setAction('1.2');

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->entitiesBuilder->single(456)
            ->shouldBeCalled()
            ->willReturn($user);

        $this->actions->setDeletedFlag(Argument::type(Entity::class), true)
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_removed_if_spam(Entity $entity)
    {
        $report = new Report;
        $report->setEntityUrn('urn:activity:123')
            ->setReasonCode(4);

        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setAction('4');

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->actions->setDeletedFlag(Argument::type(Entity::class), true)
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

}
