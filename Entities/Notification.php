<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities\DenormalizedEntity;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Helpers\Subscriptions;

/**
 * Notification Entity
 */
class Notification extends DenormalizedEntity
{
    use \Minds\Traits\CurrentUser;

    protected $type = 'notification';
    protected $guid;
    protected $notification_view;
    protected $description;
    protected $read = 0;
    protected $access_id = 2;
    protected $params;
    protected $time_created;
    protected $to;
    protected $entity;
    protected $from;
    protected $owner;
    protected $filter;

    protected $exportableDefaults = [
        'type',
        'guid',
        'notification_view',
        'description',
        'read',
        'access_id',
        'params',
        'time_created',
        'to',
        'entity',
        'from',
        'owner',
        'filter',
    ];

    protected $ttl = ((60 * 60) * 24) * 60; // 60 days to live

    /**
     * Writes the entity to the database and updates counters
     * @return Notification
     */
    public function save()
    {
        if (!$this->to) {
            throw new \UnexpectedValueException('Missing target User');
        }

        // generate a GUID (if not present) before saving
        $this->getGuid();

        $data = [
            'type' => $this->type,
            'guid' => $this->guid,
            'notification_view' => $this->notification_view,
            'description' => $this->description,
            'read' => $this->read,
            'access_id' => $this->access_id,
            'params' => $this->params ?: (object) [],
            'time_created' => $this->time_created,
            'to' => $this->to,
            'entity' => $this->entity,
            'from' => $this->from,
            'owner' => $this->owner,
            'filter' => $this->getFilter(),
        ];

        // Might-be-exportable properties
        foreach (['to', 'entity', 'from', 'owner'] as $exportable) {
            if (is_object($data[$exportable]) && method_exists($data[$exportable], 'export')) {
                $data[$exportable] = $data[$exportable]->export();
            }
        }

        $to = $this->to;

        if (isset($this->to->guid)) {
            $to = $this->to->guid;
        } elseif (isset($this->to['guid'])) {
            $to = $this->to['guid'];
        }

        $this->rowKey = 'notifications:' . $to;
        $this->saveToDb($data);

        if ($filter = $this->getFilter()) { //kind of ugly..
            $this->rowKey = 'notifications:' . $to . ':' . $filter;
            $this->saveToDb($data);
            $this->rowKey = 'notifications:' . $to;
        }

        return $this;
    }

    /**
     * Returns the value of `type` property
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of `type` property
     * @param $type mixed
     * @return Entities\Notification
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the value of `type` property. Generates it if doesn't exist
     * @return mixed
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->guid = Core\Guid::build();
            $this->time_created = time();
        }

        return $this->guid;
    }

    /**
     * Sets the value of `guid` property
     * @param $guid mixed
     * @return Entities\Notification
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Returns the value of `notification_view` property
     * @return mixed
     */
    public function getNotificationView()
    {
        return $this->notification_view;
    }

    /**
     * Sets the value of `notification_view` property
     * @param $notification_view mixed
     * @return Entities\Notification
     */
    public function setNotificationView($notification_view)
    {
        $this->notification_view = $notification_view;
        return $this;
    }

    /**
     * Returns the value of `description` property
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of `description` property
     * @param $description mixed
     * @return Entities\Notification
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns the value of `read` property
     * @return mixed
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Sets the value of `read` property
     * @param $read mixed
     * @return Entities\Notification
     */
    public function setRead($read)
    {
        $this->read = $read;
        return $this;
    }

    /**
     * Returns the value of `access_id` property
     * @return mixed
     */
    public function getAccessId()
    {
        return $this->access_id;
    }

    /**
     * Sets the value of `access_id` property
     * @param $access_id mixed
     * @return Entities\Notification
     */
    public function setAccessId($access_id)
    {
        $this->access_id = $access_id;
        return $this;
    }

    /**
     * Returns the value of `params` property
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets the value of `params` property
     * @param $params mixed
     * @return Entities\Notification
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Returns the value of `time_created` property
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * Sets the value of `time_created` property
     * @param $time_created mixed
     * @return Entities\Notification
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    /**
     * Returns the value of `to` property
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets the value of `to` property
     * @param $to mixed
     * @return Entities\Notification
     */
    public function setTo($to)
    {
        $this->to = is_numeric($to) ? Factory::build($to) : $to;
        return $this;
    }

    /**
     * Returns the value of `entity` property
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the value of `entity` property
     * @param $entity mixed
     * @return Entities\Notification
     */
    public function setEntity($entity)
    {
        $this->entity = is_numeric($entity) ? Factory::build($entity) : $entity;
        return $this;
    }

    /**
     * Returns the value of `from` property
     * @return mixed
     */
    public function getFrom()
    {
        if(!$this->from && $this->notification_view == 'friends'){
            $this->from = $this->entity;
        }
        if (Core\Session::isLoggedIn()) {
            $this->from['subscribed'] = Core\Session::getLoggedInUser()->isSubscribed((int) $this->from['guid']);
            $this->from['subscriber'] = Core\Session::getLoggedInUser()->isSubscriber((int) $this->from['guid']);
            //$this->from['subscribed'] = true;
        }
        return $this->from;
    }

    /**
     * Sets the value of `from` property
     * @param $from mixed
     * @return Entities\Notification
     */
    public function setFrom($from)
    {
        $this->from = is_numeric($from) ? Factory::build($from) : $from;
        return $this;
    }

    /**
     * Returns the value of `owner` property
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets the value of `owner` property
     * @param $owner mixed
     * @return Entities\Notification
     */
    public function setOwner($owner)
    {
        $this->owner = is_numeric($owner) ? Factory::build($owner) : $owner;
        return $this;
    }

    /**
     * Returns the value of `filter` property
     * @return mixed
     */
    public function getFilter()
    {
        switch ($this->notification_view) {
            case 'friends':
            case 'missed_call':
            case 'welcome_chat':
            case 'welcome_discover':
                return 'subscriptions';
                break;
            case 'group_invite':
            case 'group_kick':
            case 'group_activity':
                return 'groups';
                break;
            case 'comment':
                return 'comments';
            case 'like':
            case 'downvote':
                return 'votes';
            case 'remind':
                return 'reminds';
            case 'tag':
                return 'tags';
            case 'boost_gift':
            case 'boost_submitted':
            case 'boost_submitted_p2p':
            case 'boost_request':
            case 'boost_rejected':
            case 'boost_accepted':
            case 'boost_completed':
            case 'boost_peer_request':
            case 'boost_peer_accepted':
            case 'boost_peer_rejected':
            case 'welcome_points':
            case 'welcome_boost':
                return 'boosts';
        }
        return 'other';
    }

    /**
     * Sets the value of `filter` property
     * @param $filter mixed
     * @return Entities\Notification
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }
}
