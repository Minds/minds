<?php

/**
 * Minds Comments Manager
 *
 * @author emi
 */

namespace Minds\Core\Comments;

use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Luid;
use Minds\Core\Security\ACL;
use Minds\Exceptions\BlockedUserException;
use Minds\Exceptions\InvalidLuidException;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var Legacy\Repository */
    protected $legacyRepository;

    /** @var ACL */
    protected $acl;

    /** @var Delegates\Metrics */
    protected $metrics;

    /** @var Delegates\ThreadNotifications */
    protected $threadNotifications;

    /** @var Delegates\CreateEventDispatcher */
    protected $createEventDispatcher;

    /** @var Delegates\CountCache */
    protected $countCache;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /**
     * Manager constructor.
     * @param Repository|null $repository
     */
    public function __construct(
        $repository = null,
        $legacyRepository = null,
        $acl = null,
        $metrics = null,
        $threadNotifications = null,
        $createEventDispatcher = null,
        $countCache = null,
        $entitiesBuilder = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->legacyRepository = $legacyRepository ?: new Legacy\Repository();
        $this->acl = $acl ?: ACL::_();
        $this->metrics = $metrics ?: new Delegates\Metrics();
        $this->threadNotifications = $threadNotifications ?: new Delegates\ThreadNotifications();
        $this->createEventDispatcher = $createEventDispatcher ?: new Delegates\CreateEventDispatcher();
        $this->countCache = $countCache ?: new Delegates\CountCache();
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * Adds a comment and triggers creation events
     * @param Comment $comment
     * @return bool
     * @throws BlockedUserException
     * @throws \Exception
     */
    public function add(Comment $comment)
    {
        $entity = $this->entitiesBuilder->single($comment->getEntityGuid());

        if (
            !$comment->getOwnerGuid() ||
            !$this->acl->interact($entity, $comment->getOwnerEntity(true))
        ) {
            throw new BlockedUserException();
        }

        $success = $this->repository->add($comment);

        if ($success) {
            // NOTE: It's important to _first_ notify, then subscribe.
            $this->threadNotifications->notify($comment);
            $this->threadNotifications->subscribeOwner($comment);

            $this->metrics->push($comment);

            $this->createEventDispatcher->dispatch($comment);

            $this->countCache->destroy($comment);
        }

        return $success;
    }

    /**
     * Updates a comment and triggers updating events
     * @param Comment $comment
     * @return bool
     * @throws \Exception
     */
    public function update(Comment $comment)
    {
        return $this->repository->update($comment, $comment->getDirtyAttributes());
    }

    /**
     * Deletes a comment and triggers deletion events
     * @param Comment $comment
     * @return bool
     * @throws \Exception
     */
    public function delete(Comment $comment)
    {
        $success = $this->repository->delete($comment);

        if ($success) {
            $this->countCache->destroy($comment);
        }

        return $success;
    }

    /**
     * Get a comment using its LUID. Fallbacks to legacy GUID lookup, if needed.
     * @param Luid|string $luid
     * @return Comment|null
     * @throws \Exception
     */
    public function getByLuid($luid)
    {
        try {
            $luid = new Luid($luid);

            return $this->repository->get($luid->getEntityGuid(), $luid->getParentGuid(), $luid->getGuid());
        } catch (InvalidLuidException $e) {
            // Fallback to old GUIDs
            if (is_numeric($luid) && strlen($luid) >= 18) {
                return $this->legacyRepository->getByGuid($luid);
            }
        }

        return null;
    }

    /**
     * Counts comments on an entity
     * @param int $entity_guid
     * @param int $parent_guid
     * @return int
     */
    public function count($entity_guid, $parent_guid = null)
    {
        try {
            $count = $this->repository->count($entity_guid, $parent_guid);
        } catch (\Exception $e) {
            error_log('Comments\Manager::count ' . get_class($e) . ':' . $e->getMessage());
            $count = 0;
        }

        return $count;
    }
}
