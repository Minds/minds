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
    private $summonDelegate;

    function let(
        Repository $repository,
        EntitiesResolver $entitiesResolver,
        Delegates\NotificationDelegate $notificationDelegate,
        Delegates\SummonDelegate $summonDelegate
    )
    {
        $this->beConstructedWith($repository, $entitiesResolver, $notificationDelegate, $summonDelegate);
        $this->repository = $repository;
        $this->entitiesResolver = $entitiesResolver;
        $this->notificationDelegate = $notificationDelegate;
        $this->summonDelegate = $summonDelegate;
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

    function it_should_add_appeal_to_repository(Appeal $appeal, Report $report)
    {
        $this->repository->add($appeal)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->notificationDelegate->onAction($appeal)
            ->shouldBeCalled();

        $this->summonDelegate
            ->onAppeal(Argument::type(Appeal::class))
            ->shouldBeCalled();

        $appeal->getReport()
            ->willReturn($report);

        $report->getState()
            ->willReturn('initial_jury_decided');

        $this->appeal($appeal)
            ->shouldBe(true);
    }

    function it_should_NOT_add_appeal_to_repository_if_not_been_to_initial_jury(Appeal $appeal, Report $report)
    {
        $this->repository->add($appeal)
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this->notificationDelegate->onAction($appeal)
            ->shouldNotBeCalled();

        $appeal->getReport()
            ->willReturn($report);

        $report->getState()
            ->willReturn('reported');

        $this->shouldThrow('Minds\Core\Reports\Appeals\NotAppealableException')
            ->duringAppeal($appeal);
    }

}
