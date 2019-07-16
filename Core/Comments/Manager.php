<?php

/**
 * Minds Comments Manager
 *
 * @author emi
 */

namespace Minds\Core\Comments;

use Minds\Common\Urn;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Luid;
use Minds\Core\Security\ACL;
use Minds\Core\Session;
use Minds\Entities\User;
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

    /** @var Security\Spam */
    protected $spam;

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
        $entitiesBuilder = null,
        $spam = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->legacyRepository = $legacyRepository ?: new Legacy\Repository();
        $this->acl = $acl ?: ACL::_();
        $this->metrics = $metrics ?: new Delegates\Metrics();
        $this->threadNotifications = $threadNotifications ?: new Delegates\ThreadNotifications();
        $this->createEventDispatcher = $createEventDispatcher ?: new Delegates\CreateEventDispatcher();
        $this->countCache = $countCache ?: new Delegates\CountCache();
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->spam = $spam ?: Di::_()->get('Security\Spam');
    }

    public function get($entity_guid, $parent_path, $guid)
    {
        return $this->repository->get($entity_guid, $parent_path, $guid);
    }

    public function getList($opts = [])
    {
        $opts = array_merge([
            'entity_guid' => null,
            'parent_guid' => null,
            'guid' => null,
            'limit' => null,
            'offset' => null,
            'descending' => true,
            'is_focused' => false,
        ], $opts);

        if ($this->legacyRepository->isLegacy($opts['entity_guid'])) {
            return $this->legacyRepository->getList($opts);
        }

        $response = $this->repository->getList($opts);

        if ($opts['is_focused'] === true && $opts['offset']) {
            $count = count($response);
            $diff = $opts['limit'] - $count;
            if ($diff <= 0) {
                return $response; // no need to load anything else
            }
            $earlier = $this->repository->getList(array_merge($opts, [
                'limit' => $diff,
                'descending' => true,
                'include_offset' => false,
            ]));
            $newResponse = $earlier->reverse();
            foreach ($response as $comment) {
                $newResponse[] = $comment;
            }
            $newResponse->setPagingToken($response->getPagingToken());
            return $newResponse;
        }

        return $response;
    }

    /**
     * Adds a comment and triggers creation events
     * @param Comment $comment
     * @return bool
     * @throws BlockedUserException
     * @throws \Minds\Exceptions\StopEventException
     */
    public function add(Comment $comment)
    {
        $entity = $this->entitiesBuilder->single($comment->getEntityGuid());

        $owner = $comment->getOwnerEntity(false);

        if (!$this->acl->interact($entity, $owner, "comment")) {
            throw new \Exception();
        }

        $this->spam->check($comment);

        if (
            !$comment->getOwnerGuid() ||
            !$this->acl->interact($entity, $owner)
        ) {
            throw new BlockedUserException();
        }

        try {
            if ($this->legacyRepository->isFallbackEnabled()) {
                $this->legacyRepository->add($comment, Repository::$allowedEntityAttributes, false);
            }
        } catch (\Exception $e) {
            error_log("[Comments\Repository::add/legacy] {$e->getMessage()} > " . get_class($e));
        }

        $success = $this->repository->add($comment);

        if ($success) {
            // NOTE: It's important to _first_ notify, then subscribe.
            $this->threadNotifications->notify($comment);
            //$this->threadNotifications->subscribeOwner($comment);

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
     */
    public function update(Comment $comment)
    {
        if ($this->legacyRepository->isFallbackEnabled()) {
            $this->legacyRepository->add($comment, $comment->getDirtyAttributes(), true);
        }

        return $this->repository->update($comment, $comment->getDirtyAttributes());
    }


    /**
     * Restores a comment that was deleted from the database
     * @param Comment $comment
     * @return bool
     * @throws BlockedUserException
     */
    public function restore(Comment $comment)
    {
        $entity = $this->entitiesBuilder->single($comment->getEntityGuid());

        $owner = $comment->getOwnerEntity(false);

        if (
            !$comment->getOwnerGuid() ||
            !$this->acl->interact($entity, $owner)
        ) {
            throw new BlockedUserException();
        }

        try {
            if ($this->legacyRepository->isFallbackEnabled()) {
                $this->legacyRepository->add($comment, Repository::$allowedEntityAttributes, false);
            }
        } catch (\Exception $e) {
            error_log("[Comments\Repository::restore/legacy] {$e->getMessage()} > " . get_class($e));
        }

        $success = $this->repository->add($comment);

        if ($success) {
            $this->countCache->destroy($comment);
        }

        return $success;
    }

    /**
     * Deletes a comment and triggers deletion events
     * @param Comment $comment
     * @param array $opts
     * @return bool
     */
    public function delete(Comment $comment, $opts = [])
    {
        $opts = array_merge([
            'force' => false,
        ], $opts);

        if (!$this->acl->write($comment) && !$opts['force']) {
            return false; //TODO throw exception
        }

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

            if ($this->legacyRepository->isLegacy($luid->getEntityGuid())) {
                return $this->legacyRepository->getByGuid($luid->getGuid());
            }

            return $this->repository->get($luid->getEntityGuid(), $luid->getPartitionPath(), $luid->getGuid());
        } catch (InvalidLuidException $e) {
            // Fallback to old GUIDs
            if (is_numeric($luid) && strlen($luid) >= 18) {
                return $this->legacyRepository->getByGuid($luid);
            }
        }

        return null;
    }

    /**
     * @param string|Urn $urn
     * @return Comment|null
     * @throws \Exception
     */
    public function getByUrn($urn)
    {
        if (is_string($urn)) {
            $urn = new Urn($urn);
        }
        $components = explode(':', $urn->getNss());

        if (count($components) !== 5) {
            error_log("[CommentsManager]: Invalid Comment URN (${$components})");
            return null;
        }

        $entityGuid = $components[0];
        $parentPath = "{$components[1]}:{$components[2]}:{$components[3]}";
        $guid = $components[4];

        if ($this->legacyRepository->isLegacy($entityGuid)) {
            return $this->legacyRepository->getByGuid($guid);
        }

        return $this->repository->get($entityGuid, $parentPath, $guid);
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
