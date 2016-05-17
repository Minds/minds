<?php
/**
 * Messenger Subscriptions helper
 */
namespace Minds\Plugin\Messenger\Helpers;

use Minds\Core;
use Minds\Entities\User;

class Subscriptions
{
    protected $friendsof;

    public function __construct($friendsof = null)
    {
        $this->friendsof = $friendsof ?: new Core\Data\Call('friendsof');
    }

    /**
     * Checks if $user and $to are mutual subscribers
     * @param  mixed   $user
     * @param  mixed   $to
     * @return boolean
     */
    public function isMutual($user, $to)
    {
        $mutual = false;

        if ($user instanceof User) {
            $user = $user->guid;
        }

        if ($to instanceof User) {
            $to = $to->guid;
        }

        if ($item = $this->friendsof->getRow($user, [ 'offset' => $to, 'limit' => 1 ])) {
            if ($item && key($item) == $to) {
                $mutual = true;
            }
        }

        return $mutual;
    }
}
