<?php
/**
 * SuggestedGroups.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

class SuggestedGroupsDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return count($user->getGroupMembership() ?: []) > 0;
    }
}
