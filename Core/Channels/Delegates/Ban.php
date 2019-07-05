<?php
/**
 * BanDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;
use Minds\Entities\User;

class Ban
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    public function __construct($eventsDispatcher = null)
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * @param User $user
     * @param string $banReason
     * @return bool
     */
    public function ban(User $user, $banReason = '', $refreshCache = true)
    {
        $user->ban_reason = $banReason;
        $user->banned = 'yes';
        $user->code = '';

        $saved = (bool) $user->save();

        if ($saved) {
            if ($refreshCache) {
                \cache_entity($user);
            }

            $this->eventsDispatcher->trigger('ban', 'user', $user);
        }

        return $saved;
    }
}
