<?php
/**
 * Invitations to Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Api\Factory as ApiFactory;
use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Core\Security\ACL;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Entities\User;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Invitations;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Featured;
use Minds\Plugin\Groups\Core\Activity;

use Minds\Plugin\Groups\Behaviors\Actorable;

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class Group
{
    use Actorable;

    protected $relDB;
    protected $group;
    protected $featured;
    protected $activity;
    protected $membership;
    protected $notifications;
    protected $invitations;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(
        GroupEntity $group,
        $db = null,
        $featured = null,
        $acl = null,
        $activity = null,
        $membership = null,
        $notifications = null,
        $invitations = null
    )
    {
        $this->group = $group;
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->featured = $featured ?: new Featured();
        $this->setAcl($acl);

        // These deps are only used in export()
        // TODO: [emi] Ask Mark if makes sense moving these to the export() method
        $this->activity = $activity ?: new Activity($this->group);
        $this->membership = $membership ?: new Membership($this->group);
        $this->notifications = $notifications ?: new Notifications($this->group);
        $this->invitations = $invitations ?: new Invitations($this->group);
    }

    /**
     * Export Group's metadata and additional info. Suitable for API reponse.
     * @return array
     */
    public function export()
    {
        $group = $this->group->export();
        $group['activity:count'] = $this->activity->count();

        $actor = $this->getActor();
        $can_actor_read = $this->canActorRead($this->group);

        $group['is:invited'] = $actor ? $this->invitations->isInvited($actor) : false;
        $group['is:awaiting'] = $actor ? $this->membership->isAwaiting($actor) : false;
        $group['is:banned'] = $actor ? $this->membership->isBanned($actor) : false;

        $group['members'] = $can_actor_read ? ApiFactory::exportable($this->membership->getMembers()) : [];
        $group['members:count'] = $can_actor_read ? $this->membership->getMembersCount() : '';
        $group['is:member'] = $can_actor_read ? $this->group->isMember($actor) : false;

        if ($can_actor_read) {
            $group['is:muted'] = $this->notifications->isMuted($actor);
            $group['is:creator'] = $this->group->isCreator($actor);

            $owner = $this->group->isOwner($actor);
            $group['is:owner'] = $owner;

            if ($owner) {
                $group['requests:count'] = $this->membership->getRequestsCount();
            }
        }

        return $group;
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

        if (!$this->group->isOwner($this->getActor())) {
            throw new GroupOperationException('You cannot delete this group');
        }

        $deleted = $this->group->delete();

        if (!$deleted) {
            throw new GroupOperationException('Error deleting group');
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
