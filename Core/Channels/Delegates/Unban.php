<?php
/**
 * Unban.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates;

use Minds\Entities\User;

class Unban
{
    /**
     * @param User $user
     * @return bool
     */
    public function unban(User $user, $refreshCache = true)
    {
        $user->ban_reason = '';
        $user->banned = 'no';

        $saved = (bool) $user->save();

        if ($saved && $refreshCache) {
            \cache_entity($user);
        }

        return $saved;
    }
}
