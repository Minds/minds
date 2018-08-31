<?php

/**
 * Minds Comment Metrics
 *
 * @author emi
 */

namespace Minds\Core\Comments\Delegates;

use Minds\Core\Analytics\Metrics\Event;
use Minds\Core\Comments\Comment;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;

class Metrics
{
    /** @var Event */
    protected $metricsEvent;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /**
     * Metrics constructor.
     * @param null $metricsEvent
     * @param null $entitiesBuilder
     */
    public function __construct($metricsEvent = null, $entitiesBuilder = null)
    {
        $this->metricsEvent = $metricsEvent ?: new Event();
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * Pushes the metric event
     * @param Comment $comment
     */
    public function push(Comment $comment)
    {
        $owner = $this->entitiesBuilder->single($comment->getOwnerGuid());

        $entityGuid = $comment->getEntityGuid();
        $entity = $this->entitiesBuilder->single($entityGuid);

        $this->metricsEvent
            ->setType('action')
            ->setAction('comment')
            ->setProduct('platform')
            ->setUserGuid((string) $owner->guid)
            ->setUserPhoneNumberHash($owner->getPhoneNumberHash())
            ->setEntityGuid((string) $entity->guid)
            ->setEntityContainerGuid((string) $entity->container_guid)
            ->setEntityAccessId($entity->access_id)
            ->setEntityType($entity->type)
            ->setEntitySubtype((string) $entity->subtype)
            ->setEntityOwnerGuid((string) $entity->owner_guid)
            ->setCommentGuid((string) $comment->getLuid())
            ->push();
    }
}
