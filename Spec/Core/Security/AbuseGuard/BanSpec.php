<?php

namespace Spec\Minds\Core\Security\AbuseGuard;

use Minds\Core\Channels\Ban as ChannelsBan;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Sessions;
use Minds\Entities\User;
use Minds\Core\Security\AbuseGuard\AccusedEntity;
use Minds\Core\Security\AbuseGuard\Recover;

class BanSpec extends ObjectBehavior
{
    /** @var Sessions */
    private $sessions;

    /** @var Recover */
    private $recover;

    /** @var ChannelsBan */
    private $channelsBanManager;

    function let(Sessions $sessions, Recover $recover, ChannelsBan $channelsBanManager) {
        $this->beConstructedWith($sessions, $recover, false, $channelsBanManager);
        $this->sessions = $sessions;
        $this->recover = $recover;
        $this->channelsBanManager = $channelsBanManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\AbuseGuard\Ban');
    }

    function it_should_set_accused(AccusedEntity $accused)
    {
        $this->setAccused($accused)->shouldReturn($this);
    }

    function it_should_ban_a_user(
        AccusedEntity $accused,
        User $user
    )
    {
        $user->get('guid')->willReturn(123);
        $user->get('banned')->willReturn('no');

        $accused->getUser()->willReturn($user);
        $accused->getScore()->willReturn(1);
        $this->setAccused($accused);

        $this->recover->setAccused($accused)->willReturn($this->recover);
        $this->recover->recover()->willReturn(true);

        $this->channelsBanManager->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->channelsBanManager);

        $this->channelsBanManager->ban(8)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->ban()->shouldBe(true);
    }
}
