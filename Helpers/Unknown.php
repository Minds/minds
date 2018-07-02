<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Helpers;

use Minds\Entities\User;

class Unknown
{
    /**
     * @return User
     * @throws \Exception
     */
    public static function user()
    {
        $user = new User();

        $user->guid = 0;
        $user->username = '';
        $user->name = 'Unknown User';

        return $user;
    }
}
