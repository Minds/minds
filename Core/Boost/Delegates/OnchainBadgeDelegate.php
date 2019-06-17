<?php

/**
 * OnchainBadgeDelegate
 *
 * @author Ben Hayward
 */

namespace Minds\Core\Boost\Delegates;

use Minds\Core\Boost\Network\Boost;

class OnchainBadgeDelegate
{

    /**
     * Updates the timestamp of the users 'onchain_booster' to 7 days in the future. 
     * @param array $boost
     */
    public function dispatch(Boost $boost)
    {
        $user = $boost->getOwner();
        $user->setOnchainBooster(time() + 604800); //7 days
        $user->save();
    }
}
