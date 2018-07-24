<?php

/**
 * Minds Groups Feed Handler
 *
 * @author emi
 */

namespace Minds\Core\Groups;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

// TODO: Migrate to new Feeds CQL (approveAll)
class Feeds
{
    /** @var Entities\Group $group */
    protected $group;

    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;

    /**
     * Feeds constructor.
     * @param null $entitiesBuilder
     */
    public function __construct($entitiesBuilder = null)
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param Entities\Group $group
     * @return $this
     */
    public function setGroup(Entities\Group $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param array $options
     * @return array - data | next
     * @throws \Exception
     */
    public function getAll(array $options = [])
    {
        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');

        $rows = $adminQueue->getAll($this->group, $options);

        if (!$rows) {
            return [
                'data' => [],
                'next' => ''
            ];
        }

        $guids = [];

        foreach ($rows as $row) {
            $guids[] = $row['value'];
        }

        $data = [];

        if ($guids) {
            $data = Di::_()->get('Entities')->get([ 'guids' => $guids ]);
        }

        return [
            'data' => $data,
            'next' => base64_encode($rows->pagingStateToken())
        ];
    }

    public function count()
    {
        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');
        $rows = $adminQueue->count($this->group);

        if (!$rows) {
            return 0;
        }

        return (int) $rows[0]['count']->value();
    }

    /**
     * @param Entities\Activity $activity
     * @return bool
     * @throws \Exception
     */
    public function queue(Entities\Activity $activity, array $options = [])
    {
        $options = array_merge([
            'notification' => true
        ], $options);

        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        if (!$activity || !$activity->guid) {
            throw new \Exception('Invalid group activity');
        }

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');
        $success = $adminQueue->add($this->group, $activity);

        if ($success && $options['notification']) {
            $this->sendNotification('add', $activity);
        }

        return $success;
    }

    /**
     * @param Entities\Activity $activity
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function approve(Entities\Activity $activity, array $options = [])
    {
        $options = array_merge([
            'notification' => true
        ], $options);

        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        if (!$activity || !$activity->guid) {
            throw new \Exception('Invalid group activity');
        }

        if ($activity->container_guid != $this->group->getGuid()) {
            throw new \Exception('Activity doesn\'t belong to this group');
        }

        $activity->indexes = [
            "activity:container:$activity->container_guid",
            "activity:network:$activity->owner_guid"
        ];

        $activity->setPending(false);
        $activity->save(true);

        if ($activity->entity_guid) {
            $attachment = $this->entitiesBuilder->single($activity->entity_guid);

            if ($attachment && ($attachment->subtype == 'image' || $attachment->subtype == 'video') && !$attachment->getWireThreshold()) {
                $attachment->access_id = 2;
                $attachment->save();
            }
        }

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');
        $success = $adminQueue->delete($this->group, $activity);

        if ($success && $options['notification']) {
            $this->sendNotification('approve', $activity);

            (new Notifications())
                ->setGroup($this->group)
                ->queue($activity);
        }

        return $success;
    }

    /**
     * @param Entities\Activity $activity
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function reject(Entities\Activity $activity, array $options = [])
    {
        $options = array_merge([
            'notification' => true
        ], $options);

        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        if (!$activity || !$activity->guid) {
            throw new \Exception('Invalid group activity');
        }

        if ($activity->container_guid != $this->group->getGuid()) {
            throw new \Exception('Activity doesn\'t belong to this group');
        }

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');
        $success = $adminQueue->delete($this->group, $activity);

        if ($success && $options['notification']) {
            $this->sendNotification('reject', $activity);
        }

        return $success;
    }

    /**
     * @return boolean[]
     * @throws \Exception
     */
    public function approveAll()
    {
        if (!$this->group) {
            throw new \Exception('Group not set');
        }

        // TODO: Run in a queue!

        $results = [];

        /** @var AdminQueue $adminQueue */
        $adminQueue = Di::_()->get('Groups\AdminQueue');
        $rows = $adminQueue->getAll($this->group);

        foreach ($rows as $row) {
            $activity = Di::_()->get('Entities\Factory')->build($row['value']);

            $results[$activity->guid] =
                $this->approve($activity, [ 'notification' => false ]);
        }

        return $results;
    }

    /**
     * @param string $type
     * @param Entities\Activity $activity
     */
    public function sendNotification($type, Entities\Activity $activity)
    {
        Core\Events\Dispatcher::trigger('notification', 'group', [
            'to' => [ $activity->owner_guid ],
            'from' => 100000000000000519,
            'notification_view' => "group_queue_{$type}",
            'entity' => $activity,
            'params' => [
                'group' => $this->group->export()
            ]
        ]);
    }
}
