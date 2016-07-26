<?php
/**
* Groups notifications
*/
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Security;
use Minds\Core\Entities;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\Notification as NotificationEntity;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Helpers\Counters;

use Minds\Plugin\Groups\Behaviors\Actorable;

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class Notifications
{
    use Actorable;

    protected $relDB;
    protected $group;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct($relDb = null, $indexDb = null)
    {
        $this->relDB = $relDb ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->indexDb = $indexDb ?: Di::_()->get('Database\Cassandra\Indexes');
    }

    /**
     * Set the group
     * @param Group $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Queues a new Group notification
     * @param  array $activity
     * @return mixed
     */
    public function queue($activity)
    {
        $me = $activity['ownerObj']['guid'];

        return QueueClient::build()
        ->setExchange('mindsqueue')
        ->setQueue('NotificationDispatcher')
        ->send([
            'type' => 'group',
            'entity' => $this->group->getGuid(),
            'params' => [
                'activity' => $activity['guid'],
                'exclude' => [ $me ]
            ]
        ]);
    }

    /**
     * Sends a Group notification for a certain activity
     * @param  array $params
     */
    public function send($params)
    {
        $activity = EntitiesFactory::build($params['activity']);

        //generate only one notification, because it's quicker that way
        $notification = (new NotificationEntity())
            ->setTo($activity->getOwner())
            ->setEntity($activity)
            ->setFrom($activity->getOwner())
            ->setOwner($activity->getOwner())
            ->setNotificationView('group_activity')
            ->setDescription($activity->message)
            ->setParams(['group' => $this->group->export() ])
            ->setTimeCreated(time());
        $serialized = json_encode($notification->export());

        $offset = "";
        $from_user = $notification->getFrom();

        while (true) {
            echo "[notification]: Running from $offset \n";

            $guids = $this->getRecipients([
                'exclude' => $params['exclude'] ?: [],
                'limit' => 500,
                'offset' => $offset
            ]);

            if ($offset) {
                array_shift($guids);
            }

            if (!$guids) {
                break;
            }

            if ($guids[0] == $offset) {
                break;
            }

            $offset = end($guids);

            $i = 0;
            foreach ($guids as $recipient) {
                $i++;
                $pct = ($i / count($guids)) * 100;
                echo "[notification]: $i / " . count($guids) . " ($pct%) ";

                if ($from_user->guid && Security\ACL\Block::_()->isBlocked($from_user, $recipient)) {
                    continue;
                }

                $this->indexDb->set('notifications:' . $recipient, [
                    $notification->getGuid() => $serialized
                ]);
                $this->indexDb->set('notifications:' . $recipient . ':groups', [
                    $notification->getGuid() => $serialized
                ]);
                echo " (dispatched) \r";
            }

            //now update the counters for each user
            echo "\n[notification]: incrementing counters ";
            Counters::incrementBatch($guids, 'notifications:count');
            echo " (done) \n";

            if (!$offset) {
                break;
            }
        }
        echo "[notification]: Dispatch complete for $activity->guid \n";
    }

    /**
     * Gets Group notification recipients.
     * @param  array $opts
     * @return array
     */
    public function getRecipients(array $opts = [])
    {
        $opts = array_merge([
            'exclude' => [],
            'offset' => "",
            'limit' => 12
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('member', [
            'inverse' => true,
            'offset' => $opts['offset'],
            'limit' => $opts['limit']
        ]);

        $guids = array_map([ $this, 'toString' ], $guids);
        $exclude = array_unique(array_map(
            [ $this, 'toString' ],
            array_merge($opts['exclude'], $this->getMutedMembers())
        ));

        return array_values(array_diff($guids, $exclude));
    }

    /**
     * Gets the GUIDs of muted members
     * @return array
     */
    public function getMutedMembers()
    {
        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('group:muted', [
            'inverse' => true
        ]);

        if (!$guids) {
            return [];
        }

        return $guids;
    }

    /**
     * Gets the mute status for passed members
     * @param  array   $users
     * @return array
     */
    public function isMutedBatch(array $users = [])
    {
        if (!$users) {
            return [];
        }

        $muted_guids = $this->getMutedMembers();
        $result = [];

        foreach ($users as $user) {
            $result[$user] = in_array($user, $muted_guids);
        }

        return $result;
    }

    /**
     * Returns if a member has the group muted
     * @param  mixed $user
     * @return boolean
     */
    public function isMuted($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->check('group:muted', $this->group->getGuid());
    }

    /**
     * Adds an user to the muted Index list
     * @param  mixed $user
     * @return boolean
     */
    public function mute($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $done = $this->relDB->create('group:muted', $this->group->getGuid());

        if ($done) {
            return true;
        }

        throw new GroupOperationException('Error muting group');
    }

    /**
     * Removes an user from the muted Index list
     * @param  mixed $user
     * @return boolean
     */
    public function unmute($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $done = $this->relDB->remove('group:muted', $this->group->getGuid());

        if ($done) {
            return true;
        }

        throw new GroupOperationException('Error unmuting group');
    }

    /**
     * Internal funcion. Typecasts to string.
     * @param  mixed $var
     * @return string
     */
    private function toString($var)
    {
        return (string) $var;
    }
}
