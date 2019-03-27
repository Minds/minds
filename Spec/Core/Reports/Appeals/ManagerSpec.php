<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Manager;
use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Appeals\Delegates;
use Minds\Core\Reports\Report;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $entitiesBuilder;
    private $notificationDelegate;

    function let(
        Repository $repository,
        EntitiesBuilder $entitiesBuilder,
        Delegates\NotificationDelegate $notificationDelegate
    )
    {
        $this->beConstructedWith($repository, $entitiesBuilder, $notificationDelegate);
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->notificationDelegate = $notificationDelegate;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_a_list_of_hydrated_reports()
    {
        $this->repository->getList([
            'hydrate' => true,
            'showAppealed' => false
        ])
            ->shouldBeCalled()
            ->willReturn([
                (new Appeal)
                    ->setReport((new Report)
                        ->setEntityGuid(123)),
                (new Appeal)
                    ->setReport((new Report)
                        ->setEntityGuid(456)),
            ]);

        $this->entitiesBuilder->single(123)
            ->shouldBeCalled()
            ->willReturn((new Entity)->set('guid', 123));
        $this->entitiesBuilder->single(456)
            ->shouldBeCalled()
            ->willReturn((new Entity)->set('guid', 456));

        $response = $this->getList([ 'hydrate' => true ]);
        $response->shouldHaveCount(2);
        $response[0]->getReport()->getEntityGuid()
            ->shouldBe(123);
        $response[0]->getReport()->getEntity()->getGuid()
            ->shouldBe(123);
        $response[1]->getReport()->getEntityGuid()
            ->shouldBe(456);
        $response[1]->getReport()->getEntity()->getGuid()
            ->shouldBe(456);
    }

    function it_should_add_appeal_to_repository(Appeal $appeal)
    {
        $this->repository->add($appeal)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->notificationDelegate->onAction($appeal)
            ->shouldBeCalled();

        $this->appeal($appeal)
            ->shouldBe(true);
    }

}
