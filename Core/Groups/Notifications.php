<?php
/**
* Groups notifications
*/
namespace Minds\Core\Groups;

use Minds\Core\Security;
use Minds\Core\Di\Di;
use Minds\Core\Queue;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Core\Notification\Notification;
use Minds\Core\Notification\UpdateMarkers\UpdateMarker;
use Minds\Behaviors\Actorable;

use Minds\Exceptions\GroupOperationException;

class Notifications
{
    use Actorable;

    protected $relDB;
    protected $indexDB;
    protected $cql;
    protected $group;

    /** @var NotificationsManager $notifications */
    protected $notifications;

    /** @var NotificationBatches */
    protected $notificationBatches;

    protected $updateMarkers;

    /**
     * Constructor
     */
    public function __construct(
        $relDb = null,
        $indexDb = null,
        $cql = null,
        $notifications = null,
        $notificationBatches = null
    
    )
    {
        $this->relDB = $relDb ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->indexDb = $indexDb ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->notifications = $notifications ?: Di::_()->get('Notification\Manager');
        $this->notificationBatches = $notificationBatches ?: Di::_()->get('Notification\Batches\Manager');
        $this->updateMarkers = Di::_()->get('Notification\UpdateMarkers\Manager');
    }

    /**
     * Set the group
     * @param Entities\Group $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Queues a new Group notification
     * @param  Entities\Activity|array $activity
     * @return mixed
     */
    public function queue($markerId)
    {
        $marker = new UpdateMarker();
        $marker
            ->setFromGuid($this->actor->guid)
            ->setEntityType('group')
            ->setEntityGuid($this->group->getGuid())
            ->setMarker($markerId)
            ->setUpdatedTimestamp(time());

        return Queue\Client::build()
            ->setExchange('mindsqueue')
            ->setQueue('UpdateMarkerDispatcher')
            ->send([
                'marker' => serialize($marker),
            ]);
    }

    /**
     * Sends a Group notification for a certain activity
     * @param  array $params
     */
    public function send($marker)
    {
        foreach ($this->getAllMembers() as $guid) {
            if ($guid == $marker->getFromGuid()) {
                continue;
            }
            $marker->setUserGuid($guid);
            $this->updateMarkers->add($marker);
        }
    }

    public function getAllMembers()
    {
        $this->relDB->setGuid($this->group->getGuid());
        $offset = '';
        while (true) {
            $guids = $this->relDB->get('member', [
                'inverse' => true,
                'offset' => $offset,
                'limit' => 100,
            ]);

            if ($offset) {
                unset($guids[0]);
            }

            if (!$guids) {
                return;
            }

            foreach ($guids as $guid) {
                if ($guid == $offset) {
                    return;
                }
                yield $guid;
                $offset = $guid;
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
        $exclude = array_unique(array_map([ $this, 'toString' ], $opts['exclude']));

        $mutedRows = $this->getMutedMembers(10000);

        foreach ($mutedRows as $muted) {
            $muted_guid = $muted['column1'];
            if (($index = array_search($muted_guid, $guids)) === false) {
                continue;
            }

            unset($guids[$index]);
        }

        return array_values(array_diff($guids, $exclude));
    }

    /**
     * Gets the GUIDs of muted members
     * @return array
     */
    public function getMutedMembers($limit = 10000)
    {
        $query = 'SELECT * from relationships WHERE key = ? LIMIT ?';
        $values = [ "{$this->group->getGuid()}:group:muted:inverted", (int) $limit ];

        $prepared = new Prepared\Custom();
        $prepared->query($query, $values);

        return $this->cql->request($prepared);
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

        $mutedRows = $this->getMutedMembers(10000);
        $result = [];

        foreach ($users as $user) {
            $result[(string) $user] = false;
        }

        foreach ($mutedRows as $muted) {
            $muted_guid = $muted['column1'];
            if (!isset($result[$muted_guid])) {
                continue;
            }

            $result[$muted_guid] = true;
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

        $this->notificationBatches->setUser($user_guid);
        $this->notificationBatches->setBatchId($this->group->getGuid());

        return !$this->notificationBatches->isSubscribed();
    }

    /**
     * Adds an user to the notifictions batch
     * @param  mixed $user
     * @return boolean
     */
    public function mute($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        $this->notificationBatches->setUser($user_guid);
        $this->notificationBatches->setBatchId($this->group->getGuid());

        $done = $this->notificationBatches->unSubscribe();

        if ($done) {
            return true;
        }

        throw new GroupOperationException('Error muting group');
    }

    /**
     * Removes an user from the group notification batch
     * @param  mixed $user
     * @return boolean
     */
    public function unmute($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        $this->notificationBatches->setUser((int) $user_guid);
        $this->notificationBatches->setBatchId($this->group->getGuid());
        
        $done = $this->notificationBatches->subscribe();

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
