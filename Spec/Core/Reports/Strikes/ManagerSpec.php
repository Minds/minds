<?php

namespace Spec\Minds\Core\Reports\Strikes;

use Minds\Core\Reports\Manager as ReportsManager;
use Minds\Core\Reports\Strikes\Manager;
use Minds\Core\Reports\Strikes\Delegates;
use Minds\Core\Reports\Strikes\Repository;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $reportsManager;
    private $emailDelegate;

    function let(
        Repository $repository,
        ReportsManager $reportsManager,
        Delegates\EmailDelegate $emailDelegate
    )
    {
        $this->beConstructedWith($repository, $reportsManager, $emailDelegate);
        $this->repository = $repository;
        $this->reportsManager = $reportsManager;
        $this->emailDelegate = $emailDelegate;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_a_list_of_strikes_from_repository()
    {
        $this->repository->getList(Argument::that(function($opts) {
            return $opts['user'] == (new User())->set('guid', 123);
        }))
            ->shouldbeCalled()
            ->willReturn([
                (new Strike)
                    ->setUserGuid(123),
            ]);

        $strikes = $this->getList([
            'user' => (new User())->set('guid', 123)
        ]);

        $strikes[0]->getUserGuid()
            ->shouldBe(123);
    }

    function it_should_add_to_repository(Strike $strike)
    {
        $this->repository->add($strike)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($strike)
            ->shouldReturn(true);
    }

}
