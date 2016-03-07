<?php
/**
* Groups notifications
*/
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class Notifications
{
    protected $relDB;
    protected $group;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(GroupEntity $group, $db = null)
    {
        $this->group = $group;
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
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

        $offset = '';

        while (true) {

            $guids = $this->getRecipients([
                'exclude' => $params['exclude'] ?: [],
                'limit' => 500,
                'offset' => $offset
            ]);

            if (!$guids) {
                break;
            }

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

            foreach ($guids as $recipient) {
                Dispatcher::trigger('notification', 'all', [
                    'to' => $guids,
                    'entity' => $activity,
                    'notification_view' => 'group_activity',
                    'description' => $activity->message,
                    'title' => $activity->title,
                    'params' => [
                        'group' => $this->group->getGuid()
                    ]
                ]);
            }

            if (!$offset) {
                break;
            }

        }
    }

    /**
     * Gets Group notification recipients.
     * @param  array $opts
     * @return array
     */
    public function getRecipients(array $opts = [])
    {
        $opts = array_merge([
            'exclude' => []
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('member', [
            'inverse' => true
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
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->create('group:muted', $this->group->getGuid());
    }

    /**
     * Removes an user from the muted Index list
     * @param  mixed $user
     * @return boolean
     */
    public function unmute($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->remove('group:muted', $this->group->getGuid());
    }

    /**
     * Sends a kick notification to a certain user
     * @param  mixed $user
     * @return boolean
     */
    public function sendKickNotification($user)
    {
        if (!$user) {
            return false;
        }

        Dispatcher::trigger('notification', 'all', [
            'from' => 100000000000000519,
            'to' => [ $user ],
            'notification_view' => 'group_kick',
            'params' => [
                'group' => $this->group->export()
            ]
        ]);

        return true;
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
