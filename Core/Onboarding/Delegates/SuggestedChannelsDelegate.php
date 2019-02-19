<?php
/**
 * SuggestedChannels.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

class SuggestedChannelsDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return $user->getSubscriptonsCount() > 1; // Channels are always subscribed to @minds
    }
}
