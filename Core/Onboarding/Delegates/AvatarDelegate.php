<?php
/**
 * Avatar.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Entities\User;

class AvatarDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        /*$lastAvatarUpload = $user->getLastAvatarUpload();
        $timeCreated = $user->time_created;

        $isLegacyUser = !$lastAvatarUpload || $lastAvatarUpload == 0;

        return $isLegacyUser || $lastAvatarUpload > $timeCreated;*/
	return true; // TODO fix this
    }
}
