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
                'entity' => $entity,
                'params' => ['title' => $entity->title ?: $entity->message]
            ]);
        });

        // Analytics events

        Dispatcher::register('vote', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();
            $actor = $vote->getActor();

            $container_guid = $entity->type === 'comment' ? $entity->parent->container_guid : $entity->container_guid;

            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setProduct('platform')
                ->setUserGuid((string) $actor->guid)
                ->setUserPhoneNumberHash($actor->getPhoneNumberHash())
                ->setEntityGuid((string) $entity->guid)
                ->setEntityContainerGuid((string) $container_guid)
                ->setEntityAccessId($entity->access_id)
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
                        $guid = $entity->custom_data['guid'];
                        break;
                    case 'batch':
                        $subtype = 'image';
                        $guid = $entity->entity_guid;
                        break;
                }

                $event = new Core\Analytics\Metrics\Event();
                $event->setType('action')
                    ->setProduct('platform')
                    ->setUserGuid((string) $actor->guid)
                    ->setUserPhoneNumberHash($actor->getPhoneNumberHash())
                    ->setEntityGuid($guid)
                    ->setEntityContainerGuid((string) $container_guid)
                    ->setEntityAccessId($entity->access_id)
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

            $container_guid = $entity->type === 'comment' ? $entity->parent->container_guid : $entity->container_guid;

            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setProduct('platform')
                ->setUserGuid((string) $actor->guid)
                ->setUserPhoneNumberHash($actor->getPhoneNumberHash())
                ->setEntityGuid((string) $entity->guid)
                ->setEntityContainerGuid((string) $container_guid)
                ->setEntityAccessId($entity->access_id)
                ->setEntityType($entity->type)
                ->setEntitySubtype((string) $entity->subtype)
                ->setEntityOwnerGuid((string) $entity->owner_guid)
                ->setAction("vote:{$direction}:cancel")
                ->push();
        });

    }
}
