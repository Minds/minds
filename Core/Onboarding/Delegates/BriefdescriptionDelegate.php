<?php
/**
 * Briefdescription.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

class BriefdescriptionDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return (bool) $user->briefdescription;
    }
}
