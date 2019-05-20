<?php

namespace Spec\Minds\Core\Reports\Summons\Delegates;

use Minds\Core\Reports\Summons\Delegates\SocketDelegate;
use Minds\Core\Reports\Summons\Summons;
use Minds\Core\Sockets\Events as SocketEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SocketDelegateSpec extends ObjectBehavior
{
    /** @var SocketEvents */
    protected $socketEvents;

    function let(
        SocketEvents $socketEvents
    )
    {
        $this->beConstructedWith($socketEvents);
        $this->socketEvents = $socketEvents;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SocketDelegate::class);
    }

    function it_should_emit_on_summon(Summons $summons)
    {
        $summons->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $summons->jsonSerialize()
            ->shouldBeCalled()
            ->willReturn([ 'mock' => 'phpspec' ]);

        $this->socketEvents->setUser(1000)
            ->shouldBeCalled()
            ->willReturn($this->socketEvents);

        $this->socketEvents->emit('moderation_summon', json_encode([ 'mock' => 'phpspec' ]))
            ->shouldBeCalled();

        $this
            ->onSummon($summons);
    }
}
