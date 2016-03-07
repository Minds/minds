<?php
/**
 * Invitations to Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Invitations;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Featured;

class Group
{
    protected $relDB;
    protected $group;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(GroupEntity $group, $db = null, $featured = null)
    {
        $this->group = $group;
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->featured = $featured ?: new Featured();
    }

    /**
     * Features the group
     * @return string
     */
    public function feature()
    {
        $this->group->setFeatured(1);

        if (!$this->group->getFeaturedId()) {
            $this->group->setFeaturedId(Guid::build());
        }

        $this->group->save();

        $this->featured->feature($this->group);

        return $this->group->getFeaturedId();
    }

    /**
     * Removes this group featured flag
     * @return boolean
     */
    public function unfeature()
    {
        $this->featured->unfeature($this->group);

        $this->group->setFeatured(0);
        $this->group->setFeaturedId(null);
        $this->group->save();

        return true;
    }

    /**
     * Deletes a group and schedules its contents for deletion
     * @return boolean
     */
    public function delete(array $opts = [])
    {
        $opts = array_merge([
            'cleanup' => true
        ], $opts);

        $deleted = $this->group->delete();

        if (!$deleted) {
            return false;
        }

        $this->featured->unfeature($this->group);

        if ($opts['cleanup']) {
            QueueClient::build()->setExchange('mindsqueue')
            ->setQueue('FeedCleanup')
            ->send([
                'guid' => $this->group->getGuid(),
                'owner_guid' => $this->group->getOwnerObj()->guid,
                'type' => $this->group->getType()
            ]);

            QueueClient::build()->setExchange('mindsqueue')
            ->setQueue('CleanupDispatcher')
            ->send([
                'type' => 'group',
                'group' => $this->group->export()
            ]);
        }
        
        return true;
    }
}
