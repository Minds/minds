<?php
/**
 * TokensVerification.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

class TokensVerificationDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return (bool) $user->getPhoneNumberHash();
    }
}
