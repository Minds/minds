<?php

/**
 * Features Manager
 *
 * @author emi
 */

namespace Minds\Core\Features;

use Minds\Core\Di\Di;
use Minds\Core\Session;

class Manager
{

    /** @var User $user */
    private $user;

    /**
     * Set the user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Checks if a featured is enabled
     * @param $feature
     * @return bool
     */
    public function has($feature)
    {
        $features = Di::_()->get('Config')->get('features') ?: [];

        if (!isset($features[$feature])) {
            error_log("[Features\Manager] Feature '{$feature}' is not declared. Assuming true.");

            return true;
        }

        if ($features[$feature] === 'admin' && $this->user->isAdmin()) {
            return true;
        }

        return $features[$feature] === true;
    }

    /**
     * Exports the features array
     * @return array
     */
    public function export()
    {
        return Di::_()->get('Config')->get('features') ?: [];
    }
}
