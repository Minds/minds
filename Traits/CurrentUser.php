<?php
namespace Minds\Traits;

use Minds\Core\Session;

/**
 * Trait to read current user's session
 * @todo Deprecate this.
 * @deprecated Remove in favour of DI.
 */
trait CurrentUser
{
    /**
     * Gets the currently logged in user
     * @return mixed
     */
    protected static function getCurrentUser()
    {
        return Session::getLoggedinUser();
    }

    /**
     * Gets the currently logged in user GUID
     * @return mixed
     */
    protected static function getCurrentUserGuid()
    {
        $user = static::getCurrentUser();

        if (empty($user)) {
            return null;
        }

        return $user->guid;
    }
}
