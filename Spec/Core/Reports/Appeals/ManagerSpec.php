<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Manager;
use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Appeals\Delegates;
use Minds\Core\Reports\Report;
use Minds\Core\Entities\Resolver as EntitiesResolver;
use Minds\Entities\Entity;
use Minds\Common\Urn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $entitiesResolver;
    private $notificationDelegate;

    function let(
        Repository $repository,
        EntitiesResolver $entitiesResolver,
        Delegates\NotificationDelegate $notificationDelegate
    )
    {
        $this->beConstructedWith($repository, $entitiesResolver, $notificationDelegate);
        $this->repository = $repository;
        $this->entitiesResolver = $entitiesResolver;
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
                        ->setEntityUrn('urn:activity:123')),
                (new Appeal)
                    ->setReport((new Report)
                        ->setEntityUrn('urn:activity:456')),
            ]);

        $this->entitiesResolver->single(Argument::that(function ($urn) {
            return $urn->getNss() == 123;
        }))
            ->shouldBeCalled()
            ->willReturn((new Entity)->set('guid', 123));
        $this->entitiesResolver->single(Argument::that(function ($urn) {
            return $urn->getNss() == 456;
        }))
            ->shouldBeCalled()
            ->willReturn((new Entity)->set('guid', 456));

        $response = $this->getList([ 'hydrate' => true ]);
        $response->shouldHaveCount(2);
        $response[0]->getReport()->getEntityUrn()
            ->shouldBe('urn:activity:123');
        $response[0]->getReport()->getEntity()->getGuid()
            ->shouldBe(123);
        $response[1]->getReport()->getEntityUrn()
            ->shouldBe('urn:activity:456');
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
