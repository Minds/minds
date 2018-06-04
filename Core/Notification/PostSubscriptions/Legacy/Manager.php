<?php

/**
 * Minds PostSubscriptions Legacy Manager
 *
 * @author emi
 */

namespace Minds\Core\Notification\PostSubscriptions\Legacy;

use Minds\Common\Repository\Response;
use Minds\Core\Data\Call;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\Entity;
use Minds\Core\Notification\PostSubscriptions\PostSubscription;

class Manager
{
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Call */
    protected $indexes;

    /** @var mixed */
    protected $entity_guid;

    /**
     * Manager constructor.
     * @param null $entitiesBuilder
     */
    public function __construct($entitiesBuilder = null, $indexes = null)
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->indexes = $indexes ?: new Call('entities_by_time');
    }

    /**
     * @param mixed $entity_guid
     * @return Manager
     */
    public function setEntityGuid($entity_guid)
    {
        $this->entity_guid = $entity_guid;
        return $this;
    }

    /**
     * Get a list of PostSubscriptions for an entity based on the legacy indexes
     * @return Response
     */
    public function getSubscriptions()
    {
        $entity = $this->entitiesBuilder->single($this->entity_guid);

        if ($entity->type === 'group') {
            return new Response();
        }

        $subscriptions = [];

        // Fetch all

        $subscriberGuids = $this->indexes->getRow("comments:subscriptions:{$this->entity_guid}", [ 'limit' => 5000 ]);

        if ($subscriberGuids) {
            foreach ($subscriberGuids as $subscriberGuid => $value) {
                $subscriptions[(string) $subscriberGuid] = true;
            }
        }
 
        // Owner should be subscribed regardless

        $subscriptions[(string) $entity->owner_guid] = true;

        // Apply mutes

        $mutedSubscriberGuids = (new Entity($entity))->getMutedUsers();

        foreach ($mutedSubscriberGuids as $mutedSubscriberGuid) {
            $subscriptions[(string) $mutedSubscriberGuid] = false;
        }

        // Build entities

        $postSubscriptions = new Response();

        foreach ($subscriptions as $userGuid => $following) {
            $postSubscription = new PostSubscription();
            $postSubscription
                ->setEntityGuid($this->entity_guid)
                ->setUserGuid($userGuid)
                ->setFollowing($following);

            $postSubscriptions[] = $postSubscription;
        }

        return $postSubscriptions;
    }
}