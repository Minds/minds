<?php
/**
 * SuggestedHashtags.
 *
 * @author emi
 */

namespace Minds\Core\Onboarding\Delegates;

use Minds\Core\Hashtags\User\Manager;
use Minds\Entities\User;

class SuggestedHashtagsDelegate implements OnboardingDelegate
{
    /** @var Manager */
    protected $userHashtagsManager;

    /**
     * SuggestedHashtags constructor.
     * @param null $userHashtagsManager
     */
    public function __construct($userHashtagsManager = null)
    {
        $this->userHashtagsManager = $userHashtagsManager ?: new Manager();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        $userHashtags = $this->userHashtagsManager
            ->setUser($user)
            ->get(['limit' => 1]);

        return $userHashtags && count($userHashtags) > 0 && $userHashtags[0]['selected'];
    }
}
