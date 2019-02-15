<?php

namespace Spec\Minds\Core\Suggestions\Pass;

use Minds\Core\Suggestions\Pass\Repository;
use Minds\Core\Suggestions\Pass\Pass;
use Minds\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{

    private $es;

    function let(Client $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_pass(Pass $pass)
    {
        $pass->getSuggestedGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $pass->getUserGuid()
            ->shouldBeCalled()
            ->willReturn(123);

        $this->es->request(Argument::that(function($prepared) {
                $query = $prepared->build();
                return $query['id'] == 123
                    && $query['body']['script']['params']['guid'] == 456;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($pass)
            ->shouldReturn(true);
    }

}
