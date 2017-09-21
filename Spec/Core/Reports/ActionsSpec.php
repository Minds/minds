<?php

namespace Spec\Minds\Core\Reports;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core;
use Minds\Core\Di\Di;

class ActionsSpec extends ObjectBehavior
{
    public $_repository;

    function let(
        Core\Reports\Repository $repository
    )
    {
        $this->_repository = $repository;
        Di::_()->bind('Reports\Repository', function($di) use ($repository) {
            return $repository->getWrappedObject();
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Reports\Actions');
    }

    function it_should_archive_a_report_and_return_true()
    {
        $this->_repository->update(5000, [ 'state' => 'archived' ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->archive(5000)->shouldReturn(true);
    }

    function it_should_archive_a_report_and_return_false()
    {
        $this->_repository->update(5000, [ 'state' => 'archived' ])
            ->shouldBeCalled()
            ->willReturn(false);

        $this->archive(5000)->shouldReturn(false);
    }

    // TODO: make Entities\Factory mockable
    // TODO: make Events\Dispatcher mockable
    // TODO: move helper functions (flag, etc) to another mockable class
}

