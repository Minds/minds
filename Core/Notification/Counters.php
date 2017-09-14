<?php
/**
 * Notifications Counter functions
 */

namespace Minds\Core\Notification;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

class Counters
{
    use \Minds\Traits\CurrentUser;

    protected $db;
    protected $user;

    public function __construct($db = null)
    {
        $this->db = $db ?: new Core\Data\Call('entities_by_time');
        $this->user = Core\Session::getLoggedInUser();
    }

    /**
     * Set the user
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Gets the notifications counter value for current user
     * @param  Entity $user    User. Use `null` to get the current one.
     * @param  array  $options
     * @return int
     */
    public function getCount(array $options = [])
    {
        return Helpers\Counters::get($this->user, 'notifications:count', false);
    }

    /**
     * Updates the notifications counter value for an user
     * @param  User   $user
     * @return null
     */
    public function increaseCounter()
    {
        Helpers\Counters::increment($this->user, 'notifications:count');
    }

    /**
     * Sets the notifications counter value to 0 for an user
     * @param  User   $user
     * @return null
     */
    public function resetCounter()
    {
        Helpers\Counters::clear($this->user, 'notifications:count');
    }
}
