<?php

/**
 * Minds Comments Thread Notifications
 *
 * @author emi
 */

namespace Minds\Core\Comments\Delegates;

use Minds\Core\Comments\Comment;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Notification\PostSubscriptions\Manager;

class ThreadNotifications
{
    /** @var Manager */
    protected $postSubscriptionsManager;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Dispatcher */
    private $eventsDispatcher;

    /**
     * ThreadNotifications constructor.
     * @param null $indexes
     */
    public function __construct($postSubscriptionsManager = null, $entitiesBuilder = null, $eventsDispatcher = null)
    {
        $this->postSubscriptionsManager = $postSubscriptionsManager ?: new Manager();
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * Subscribes the Comment owner to the thread
     * @param Comment $comment
     */
    public function subscribeOwner(Comment $comment)
    {
        $this->postSubscriptionsManager
            ->setEntityGuid($comment->getEntityGuid())
            ->setUserGuid($comment->getOwnerGuid())
            ->follow(false);
    }

    /**
     * Notifies all thread subscribers about new comment
     * @param Comment $comment
     * @throws \Minds\Exceptions\StopEventException
     */
    public function notify(Comment $comment)
    {
        $this->postSubscriptionsManager
            ->setEntityGuid($comment->getEntityGuid());

        $subscribers = $this->postSubscriptionsManager->getFollowers()
            ->filter(function ($userGuid) use ($comment) {
                // Exclude current comment creator
                return $userGuid != $comment->getOwnerGuid();
            }, false)
            ->toArray();

        if (!$subscribers) {
            return;
        }

        $entity = $this->entitiesBuilder->single($comment->getEntityGuid());

        if ($entity && $entity->type !== 'group') {
            $this->eventsDispatcher->trigger('notification', 'all', array(
                'to' => $subscribers,
                'entity' => (string) $comment->getEntityGuid(),
                'description' => (string) $comment->getBody(),
                'notification_view' => 'comment'
            ));
        }
    }
}
