<?php
/**
 * Onchain badge granting delegate spec test.
 *
 * @author Ben Hayward
 */

namespace Spec\Minds\Core\Boost\Delegates;

use Minds\Core\Boost\Network\Boost;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Boost\Delegates\OnchainBadgeDelegate;
use Minds\Entities\User;

class OnchainBadgeDelegateSpec extends ObjectBehavior
{
    private $user;
    
    function let(User $user)
    {
        $this->user = $user;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OnchainBadgeDelegate::class);
    }

    function it_should_update_a_users_plus_badge_expiry(Boost $boost, User $user)
    {   
        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($user);

        $user->setOnchainBooster(time() + 604800)
            ->shouldBeCalled()
            ->willReturn(null);

        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->dispatch($boost);   
    }

}
