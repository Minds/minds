<?php

namespace Spec\Minds\Core\Reports\Jury;

use Minds\Core\Reports\Jury\Manager;
use Minds\Core\Reports\Verdict\Manager as VerdictManager;
use Minds\Core\Reports\Jury\Repository;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Report;
use Minds\Core\EntitiesBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $entitiesBuilder;
    private $verdictManager;

    function let(Repository $repository, EntitiesBuilder $entitiesBuilder, VerdictManager $verdictManager)
    {
        $this->beConstructedWith($repository, $entitiesBuilder, $verdictManager);
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->verdictManager = $verdictManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_an_undmoderated_list_to_jury_on()
    {
        $this->repository->getList(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([
                (new Report)
                    ->setEntityGuid(123),
                (new Report)
                    ->setEntityGuid(456),
            ]);
        
        $this->entitiesBuilder->single(123)
            ->shouldBeCalled();
        $this->entitiesBuilder->single(456)
            ->shouldBeCalled();
        
        $response = $this->getUnmoderatedList([ 'hydrate' => true ]);
        $response->shouldHaveCount(2);
    }

    function it_should_cast_a_jury_decision(Decision $decision)
    {
        $report = new Report();

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->repository->add($decision)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->verdictManager->decideFromReport(Argument::type(Report::class))
            ->shouldBeCalled();

        $this->cast($decision)
            ->shouldBe(true);
    }

}
