<?php
/**
 * Notifications Counter functions
 */

namespace Minds\Core\Notification;

use Minds\Core;
use Minds\Core\Features\Manager as FeaturesManager;
use Minds\Entities;
use Minds\Helpers;
use Minds\Core\Di\Di;

class Counters
{
    use \Minds\Traits\CurrentUser;

    /** @var $sql */
    private $sql;

    /** @var FeaturesManager */
    private $features;

    /** @var User $user */
    private $user;

    public function __construct($sql = null, $features = null)
    {
        $this->user = Core\Session::getLoggedInUser();
        $this->features = $features ?: new FeaturesManager;

        if (!$this->features->has('cassandra-notifications')) {
            $this->sql = $sql ?: Di::_()->get('Database\PDO');
        }
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
        if ($this->features->has('cassandra-notifications')) {
            return Helpers\Counters::get($this->user, 'notifications:count', false);
        }

        // TODO: Remove below once settled

        $query = "SELECT uuid, read_timestamp FROM notifications
                    WHERE to_guid = ?
                    ORDER BY created_timestamp DESC
                    LIMIT 6";
        
        if (!$this->user) {
            return;
        }

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
        Helpers\Counters::increment($this->user, 'notifications:count');
    }

    /**
     * Sets the notifications counter value to 0 for an user
     * @param  User   $user
     * @return void
     */
    public function resetCounter()
    {
        Helpers\Counters::clear($this->user, 'notifications:count');

        // TODO: Remove below once settled

        if (!$this->features->has('cassandra-notifications')) {
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
}
