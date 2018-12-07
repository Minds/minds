<?php
/**
 * Notifications Counter functions
 */

namespace Minds\Core\Notification;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Core\Di\Di;

class Counters
{
    use \Minds\Traits\CurrentUser;

    /** @var $sql */
    private $sql;

    /** @var User $user */
    private $user;

    public function __construct($sql = null)
    {
        $this->sql = $sql ?: Di::_()->get('Database\PDO');
        $this->user = Core\Session::getLoggedInUser();
    }

    /**
     * Set the user
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        if (is_numeric($user)) {
            $guid = $user;
            $user = (new Entities\User);
            $user->guid = $guid;
        }
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
        $query = "SELECT uuid, read_timestamp FROM notifications
                    WHERE to_guid = ?
                    ORDER BY created_timestamp DESC
                    LIMIT 6";
        
        $params = [
            (int) $this->user->getGuid(),
        ];

        $statement = $this->sql->prepare($query);
        $statement->execute($params);

        $unread = 0;

        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            if (!$row['read_timestamp']) {
                $unread++;
            }
        }

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $unread;
    }

    /**
     * Updates the notifications counter value for an user
     * @param  User   $user
     * @return null
     */
    public function increaseCounter()
    {
        //Helpers\Counters::increment($this->user, 'notifications:count');
    }

    /**
     * Sets the notifications counter value to 0 for an user
     * @param  User   $user
     * @return void
     */
    public function resetCounter()
    {
        $query = "BEGIN;
                    UPDATE notifications
                        SET read_timestamp = NOW()
                        WHERE to_guid = ?
                        ORDER BY created_timestamp DESC
                        LIMIT 6
                        RETURNING NOTHING;
                    COMMIT;";
        
        $params = [
            (int) $this->user->getGuid(),
        ];

        $statement = $this->sql->prepare($query);
        
        $statement->execute($params);
    }
}
