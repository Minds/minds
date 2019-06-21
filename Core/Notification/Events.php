<?php
namespace Minds\Core\Notification;

use Minds\Entities;
use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Queue;
use Minds\Core\Session;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Core\Notification\Extensions\Push;

use Minds\Helpers;
use Minds\Core\Sockets;

class Events
{

    /**
     * Centralized method to register Event handlers related to notifications
     * @return null
     */
    public static function registerEvents()
    {

        /**
         * Create a notification when triggered
         * TODO: remove this
         */
        Dispatcher::register('notification', 'all', function (Event $event) {
            $params = $event->getParameters();
            $from = null;

            if (isset($params['from'])) {
                $from = $params['from'];
            }

            if (isset($params['entity']) && $params['entity'] && !is_object($params['entity'])) {
                $params['entity'] = Entities\Factory::build($params['entity']);
            }

            $from_user = Entities\Factory::build($from ?: Session::getLoggedInUser(), [
                'cache' => true
            ]);

            if (!$from_user) {
                return; //Must be from a user
            }

            $entity = $params['entity'];
            $description = isset($params['description']) ? $params['description'] : '';

            if ($entity instanceof Core\Blogs\Blog) {
                $entity = clone $entity;
                $entity->setBody(substr($entity->getBody(), 0, 65535));
            }

            if (strlen($description) > 65535) {
                $description = substr($description, 0, 65535);
            }

            $data = $params['params'];
            $data['description'] = $description;

            $entityGuid = null;
            if ($entity) {
                $entityGuid = $entity->getGuid();
                if ($entity->getType() == 'comment') {
                    $entityGuid = (string) $entity->getLuid();
                }
            }

            $notification = new Notification;
            $notification
                ->setToGuid($params['to'])
                ->setFromGuid($from_user->getGuid())
                ->setEntityGuid($entityGuid)
                ->setEntityUrn(method_exists($entity, 'getUrn') ? $entity->getUrn() : "urn:entity:$entityGuid")
                ->setType($params['notification_view'])
                ->setData($data);
            
            try {
                Queue\Client::build()
                  ->setQueue('NotificationDispatcher')
                  ->send([
                      'notification' => serialize($notification),
                      'to' => $params['to']
                  ]);
            } catch (\Exception $e) {
            }

            $event->setResponse([
                $notification
            ]);

	});

        /**
         * Create a notification upon @mentioning on activities or comments
         */
        Dispatcher::register('create', 'all', function ($hook, $type, $entity) {
            if ($type != 'activity' && $type != 'comment') {
                return;
            }

            if ($entity->message) {
                $message = $entity->message;
            }

            if ($type == 'comment') {
                $message = $entity->getBody();
            }

            if ($entity->title) {
                $message .= $entity->title;
            }

            $remind_owner_username = null;

            if ($type == 'activity' && isset($entity->remind_object['ownerObj']['username'])) {
                $remind_owner_username = $entity->remind_object['ownerObj']['username'];
            }

            if (preg_match_all('!@(.+)(?:\s|$)!U', $message, $matches)) {
                $usernames = $matches[1];
                $to = [];

                foreach ($usernames as $username) {
                    if ($remind_owner_username && $remind_owner_username == $username) {
                        // Don't send notification to the remind owner
                        // (they already received a notification)
                        continue;
                    }

                    $user = new Entities\User(strtolower($username));

                    if ($user->guid && !Core\Security\ACL\Block::_()->isBlocked(Core\Session::getLoggedinUser(), $user)) {
                        $to[] = $user->guid;
                    }

                    //limit of tags notifications: 5
                    if (count($to) >= 5) {
                        break;
                    }
                }

                $params = [
                    'title' => $message,
                ];

                if ($entity->type === 'comment') {
                    $params['focusedCommentUrn'] = $entity->getUrn();
                }

                if ($to) {
                    Dispatcher::trigger('notification', 'all', [
                        'to' => $to,
                        'entity' => $entity,
                        'notification_view' => 'tag',
                        'description' => $message,
                        'params' => $params, 
                    ]);
                }
            }
        });

        Dispatcher::register('notification:dispatch', 'all', function (Event $event) {
            $params = $event->getParameters();
            $notification = unserialize($params['notification']);

            if (!$notification instanceof Notification) {
                return;
            }

            $entity = Entities\Factory::build($notification->getEntityGuid());
            
            if ($entity->parent_guid || method_exists($entity, 'getEntityGuid')) {
                $parentGuid = method_exists($entity, 'getEntityGuid') ? $entity->getEntityGuid() : $entity->parent_guid;
                $parent = Entities\Factory::build($parentGuid, [ 'cache' => false ]);

                if ($parent && method_exists($parent, 'getGuid')) {
                    $notification->setData(array_merge(
                        $notification->getData() ?: [],
                        [ 'parent_guid' => $parent->getGuid() ]
                    ));
                }
            }

            $counters = new Counters();

            /** @var Manager $manager */
            $manager = Core\Di\Di::_()->get('Notification\Manager');

            foreach ($params['to'] as $to_user) {

                if (
                    $notification->getFromGuid() &&
                    Core\Security\ACL\Block::_()->isBlocked($notification->getFromGuid(), $to_user)
                ) {
                    continue;
                }

                $notification->setToGuid($to_user);

                $uuid = $manager->add($notification);

                $notification->setUUID($uuid);
                
                $counters->setUser($to_user)
                  ->increaseCounter($to_user);

                $params = $notification->getData();
                $params['notification_view']  = $notification->getType();

                Push::_()->queue([
                    'uri' => 'notification',
                    'from' => $notification->getFromGuid(),
                    'to' => $notification->getToGuid(),
                    'notification' => $notification,
                    'params' => $params,
                    'count' => $counters->getCount()
                ]);

                try {
                    (new Sockets\Events())
                    ->setUser($to_user)
                    ->emit('notification', (string) $notification->getUUID());
                } catch (\Exception $e) { /* TODO: To log or not to log */
                }

                echo "[notification][{$notification->getUUID()}]: Saved {$params['notification_view']} \n";
            }
        });

        /**
         * Cron events
         */
        Dispatcher::register('cron', 'minute', [ __CLASS__, 'cronHandler' ]);
        Dispatcher::register('cron', 'daily', [ __CLASS__, 'cronHandler' ]);
        Dispatcher::register('cron', 'weekly', [ __CLASS__, 'cronHandler' ]);
    }

    public static function cronHandler($hook, $type, $params, $return = null)
    {
        if (!isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] != 'localhost') {
            return false;
        }

        // TODO: [emi] Send email notifications
    }

    /**
     * Internal funcion. Typecasts to string.
     * @param  mixed $var
     * @return string
     */
    private static function toString($var)
    {
        return (string) $var;
    }
}
