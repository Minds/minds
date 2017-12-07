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

        // Neo4j events

        Dispatcher::register('vote', 'all', function (Event $event) {
            $params = $event->getParameters();
            $direction = $event->getNamespace();

            $vote = $params['vote'];
            $entity = $vote->getEntity();

            if (
                !in_array($entity->subtype, ['video', 'image']) &&
                ($entity->type != 'activity' || !$entity->custom_data)
            ) {
                return;
            }

            $guid = $entity->guid;
            $subtype = $entity->subtype;

            if ($entity->custom_type == 'video') {
                $subtype = 'video';
                $guid = $entity->custom_data['guid'];
            } elseif ($entity->custom_type == 'batch') {
                $subtype = 'image';
                $guid = $entity->entity_guid;
            }

            /** @var Core\Data\Neo4j\Client $neo4j */
            $neo4j = Core\Di\Di::_()->get('Database\Neo4j');
            $prepared = new Core\Data\Neo4j\Prepared\Common();

            if ($direction == 'up') {
                $neo4j->request($prepared->createVoteUP($guid, $subtype));
            } elseif ($direction == 'down') {
                $neo4j->request($prepared->createVoteDOWN($guid, $subtype));
            }
        });
    }
}
