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

            $entity = $params['entity'];
            $description = isset($params['description']) ? $params['description'] : '';

            if ($entity instanceof Core\Blogs\Blog) {
                $entity = clone $entity;
                $entity->setBody(substr($entity->getBody(), 0, 65535));
            }

            if (strlen($description) > 65535) {
                $description = substr($description, 0, 65535);
            }

            $notification = (new Entities\Notification())
                ->setEntity($entity)
                ->setFrom($from_user)
                ->setNotificationView($params['notification_view'])
                ->setDescription($description)
                ->setParams($params['params'])
                ->setTimeCreated(time());

            try {
                Queue\Client::build()
                  ->setQueue('NotificationDispatcher')
                  ->send([
                      'notification' => serialize($notification),
                      'to' => $params['to']
                  ]);
            } catch (\Exception $e) {}

            $event->setResponse([
                $notification
            ]);

	});

        /**
         * Create a notification upon @mentioning on activities or comments
         */
        Dispatcher::register('create', 'all', function ($hook, $type, $params = []) {
            if ($type != 'activity' && $type != 'comment') {
                return;
            }

            if ($params->message) {
                $message = $params->message;
            }

            if ($type == 'comment') {
                $message = $params->getBody();
            }

            if ($params->title) {
                $message .= $params->title;
            }

            $remind_owner_username = null;

            if ($type == 'activity' && isset($params->remind_object['ownerObj']['username'])) {
                $remind_owner_username = $params->remind_object['ownerObj']['username'];
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

                if ($to) {
                    Dispatcher::trigger('notification', 'all', [
                        'to' => $to,
                        'entity' => $params,
                        'notification_view' => 'tag',
                        'description' => $params->message,
                        'params' => ['title' => $params->title]
                    ]);
                }
            }
        });

        Dispatcher::register('notification:dispatch', 'all', function (Event $event) {
            $params = $event->getParameters();
            $notification = unserialize($params['notification']);

            if (!$notification instanceof Entities\Notification) {
                return;
            }

            $entity = $notification->getEntity();

            if ($entity->parent_guid || method_exists($entity, 'getEntityGuid')) {
                $parentGuid = method_exists($entity, 'getEntityGuid') ? $entity->getEntityGuid() : $entity->parent_guid;
                $parent = Entities\Factory::build($parentGuid, [ 'cache' => false ]);

                if ($parent && method_exists($parent, 'export')) {
                    $exportedParent = $parent->export();

                    if (isset($exportedParent['guid'])) {
                        $exportedParent['guid'] = (string) $exportedParent['guid'];
                    }

                    $notification->setParams(array_merge(
                        $notification->getParams() ?: [],
                        [ 'parent' => $exportedParent ]
                    ));
                }
            }

            $from_user = $notification->getFrom();
            if (is_numeric($from_user) || is_string($from_user)) {
                $from_user = Entities\Factory::build($from_user);
            }

            $counters = new Counters();

            foreach ($params['to'] as $to_user) {
                if (is_numeric($to_user) || is_string($to_user)) {
                    $to_user = Entities\Factory::build((int) $to_user);
                }

                if (!$to_user) {
                    continue;
                }

                if ($from_user->guid && Core\Security\ACL\Block::_()->isBlocked($from_user, $to_user)) {
                    // echo "{$from_user->username} is blocked by {$to_user->username}, skipping.";
                    continue;
                }

                $notification->setTo($to_user)
                  ->setOwner($to_user)
                  ->save();

                $counters->setUser($to_user)
                  ->increaseCounter($to_user);

                $params = $notification->getParams();
                $params['notification_view']  = $notification->getNotificationView();

                Push::_()->queue([
                    'uri' => 'notification',
                    'from' => $notification->getFrom(),
                    'to' => $notification->getTo(),
                    'notification' => $notification,
                    'params' => $params,
                    'count' => $counters->getCount()
                ]);

                try {
                    (new Sockets\Events())
                    ->setUser($to_user)
                    ->emit('notification', (string) $notification->getGuid());
                } catch (\Exception $e) { /* TODO: To log or not to log */
                }

                echo "[notification][{$notification->getGuid()}]: Saved {$params['notification_view']} \n";
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
