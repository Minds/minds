<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Notification\PostSubscriptions;

use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;


class Manager
{
    /** @var int */
    protected $entity_guid;

    /** @var int */
    protected $user_guid;

    /** @var Repository */
    protected $repository;

    /** @var Legacy\Manager */
    protected $legacyManager;

    /**
     * Manager constructor.
     * @param Repository $repository
     */
    public function __construct($repository = null, $legacyManager = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->legacyManager = $legacyManager ?: new Legacy\Manager();
    }

    /**
     * @param int $entity_guid
     * @return Manager
     */
    public function setEntityGuid($entity_guid)
    {
        $this->entity_guid = $entity_guid;
        return $this;
    }

    /**
     * @param int $user_guid
     * @return Manager
     */
    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    /**
     * Gets user's post subscription
     * @return PostSubscription
     */
    public function get()
    {
        $postSubscription = $this->repository->get($this->entity_guid, $this->user_guid);

        if (!$postSubscription) {
            return (new PostSubscription())
                ->setEntityGuid($this->entity_guid)
                ->setUserGuid($this->user_guid)
                ->setFollowing(false)
                ->setEphemeral(true);
        }

        return $postSubscription;
    }

    /**
     * Follows an entity. If forced, it'll override any existing value.
     * @param bool $forced
     * @return bool
     */
    public function follow($forced = true)
    {
        $postSubscription = new PostSubscription();

        $postSubscription
            ->setUserGuid($this->user_guid)
            ->setEntityGuid($this->entity_guid)
            ->setFollowing(true);

        if ($forced) {
            return $this->repository->add($postSubscription);
        } else {
            return $this->repository->update($postSubscription);
        }
    }

    /**
     * Unfollows an entity. It'll will override any existing value.
     * @return bool
     */
    public function unfollow()
    {
        $postSubscription = new PostSubscription();

        $postSubscription
            ->setUserGuid($this->user_guid)
            ->setEntityGuid($this->entity_guid)
            ->setFollowing(false);

        return $this->repository->add($postSubscription);
    }

    /**
     * Unsubscribes from an entity. This will delete any link to the entity.
     * @return bool
     */
    public function unsubscribe()
    {
        $postSubscription = new PostSubscription();

        $postSubscription
            ->setUserGuid($this->user_guid)
            ->setEntityGuid($this->entity_guid);

        return $this->repository->delete($postSubscription);
    }

    /**
     * Get a list of user GUIDs that follow an entity
     * @return Response
     */
    public function getFollowers()
    {
        $subscribers = $this->repository->getList([ 'entity_guid' => $this->entity_guid ]);

        if (count($subscribers) === 0) {
            // Fetch from legacy index
            $subscribers = $this->legacyManager
                ->setEntityGuid($this->entity_guid)
                ->getSubscriptions();

            // On-the-fly migration
            foreach ($subscribers as $postSubscription) {
                $this->repository->add($postSubscription);
            }
        }

        return $subscribers
            ->filter(function (PostSubscription $postSubscription) {
                return $postSubscription->isFollowing();
            }, false)
            ->map(function (PostSubscription $postSubscription) {
                return (string) $postSubscription->getUserGuid();
            });
    }
}