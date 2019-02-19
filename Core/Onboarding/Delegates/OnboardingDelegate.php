<?php

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

/**
 * OnboardingDelegate
 *
 * @author edgebal
 */

interface OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user);
}
