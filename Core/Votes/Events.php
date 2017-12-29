<?php

/**
 * Votes Events
 *
 * @author emi
 */

namespace Minds\Core\Votes;

use Minds\Core;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Helpers\Wallet;

class Events
{
    public function register()
    {
        // Wallet events

        Dispatcher::register('vote', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            if ($entity->owner_guid != $actor->guid && $direction == 'up') {
                Wallet::createTransaction($entity->owner_guid, 5, $entity->guid, 'Vote');
            }
            if (
                $entity->remind_object && $entity->remind_object['ownerObj']['guid'] &&
                $entity->owner_guid != $entity->remind_object['ownerObj']['guid'] &&
                $entity->remind_object['ownerObj']['guid'] != $actor->guid &&
                $direction == 'up'
            ) {
                Wallet::createTransaction($entity->remind_object['ownerObj']['guid'], 5, $entity->guid, 'Vote');
            }
        });

        Dispatcher::register('vote:cancel', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            if ($entity->owner_guid != $actor->guid && $direction == 'up') {
                Wallet::createTransaction($entity->owner_guid, -5, $entity->guid, 'Vote Removed');
            } elseif (
                $entity->remind_object && $entity->remind_object['ownerObj']['guid'] &&
                $entity->owner_guid != $entity->remind_object['ownerObj']['guid'] &&
                $entity->remind_object['ownerObj']['guid'] != $actor->guid &&
                $direction == 'up'
            ) {
                Wallet::createTransaction($entity->remind_object['ownerObj']['guid'], -5, $entity->guid, 'Vote Removed');
            }
        });

        // Notification events

        Dispatcher::register('vote', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            if ($entity->owner_guid == $actor->guid) {
                return;
            }

            Dispatcher::trigger('notification', 'thumbs', [
                'to' => [ $entity->owner_guid ],
                'notification_view' => $direction == 'up' ? 'like' : 'downvote',
                'title' => $entity->title,
                'entity' => $entity
            ]);
        });

        // Analytics events

        Dispatcher::register('vote', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setProduct('platform')
                ->setUserGuid((string) $actor->guid)
                ->setEntityGuid((string) $entity->guid)
                ->setEntityType($entity->type)
                ->setEntitySubtype((string) $entity->subtype)
                ->setEntityOwnerGuid((string) $entity->owner_guid)
                ->setAction("vote:{$direction}")
                ->push();
            
            if ($entity->type == 'activity' && $entity->custom_type) {
                $subtype = '';
                switch($entity->custom_type) {
                    case 'video':
                        $subtype = 'video';
                        break;
                    case 'batch':
                        $subtype = 'image';
                        break;
                }

                $event = new Core\Analytics\Metrics\Event();
                $event->setType('action')
                    ->setProduct('platform')
                    ->setUserGuid((string) $actor->guid)
                    ->setEntityGuid((string) $entity->guid)
                    ->setEntityType('object')
                    ->setEntitySubtype($subtype)
                    ->setEntityOwnerGuid((string) $entity->owner_guid)
                    ->setAction("vote:{$direction}")
                    ->push();
            }
        });

        Dispatcher::register('vote:cancel', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setProduct('platform')
                ->setUserGuid((string) $actor->guid)
                ->setEntityGuid((string) $entity->guid)
                ->setEntityType($entity->type)
                ->setEntitySubtype((string) $entity->subtype)
                ->setEntityOwnerGuid((string) $entity->owner_guid)
                ->setAction("vote:{$direction}:cancel")
                ->push();
        });

    }
}
