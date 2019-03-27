<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Reports\Verdict\Delegates\ActionDelegate;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Actions;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionDelegateSpec extends ObjectBehavior
{
    private $entitiesBuilder;
    private $actions;

    function let(
        EntitiesBuilder $entitiesBuilder,
        Actions $actions
    )
    {
        $this->beConstructedWith($entitiesBuilder, $actions);
        $this->entitiesBuilder = $entitiesBuilder;
        $this->actions = $actions;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ActionDelegate::class);
    }

    function it_should_apply_nsfw_flags(Entity $entity)
    {
        $report = new Report;
        $report->setEntityGuid(123);
        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setAction('2.1');

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $entity->setNsfw([ 1 ])
            ->shouldBeCalled();
        $entity->save()
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_removed_if_illegal(Entity $entity)
    {
        $report = new Report;
        $report->setEntityGuid(123);
        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setAction('1.2');

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->actions->setDeletedFlag(Argument::type(Entity::class), true);

        $this->onAction($verdict);
    }

    function it_should_removed_if_spam(Entity $entity)
    {
        $report = new Report;
        $report->setEntityGuid(123);
        $verdict = new Verdict;
        $verdict->setReport($report)
            ->setAction('4');

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->actions->setDeletedFlag(Argument::type(Entity::class), true);

        $this->onAction($verdict);
    }

}
