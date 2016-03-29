<?php
namespace Minds\Core\Notification;

use Minds\Entities;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Core\Notification\Extensions\Push;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Core\Notification\Factory as NotificationFactory;

class Events
{
    use \Minds\Traits\CurrentUser;

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

            $notifications = [];

            $from_user = EntitiesFactory::build($from ?: static::getCurrentUser(), [
                'cache' => true
            ]);

            $params = array_merge([
                'to' => [],
                'entity' => null,
                'dry' => false
            ], $params);

            foreach ($params['to'] as $to_user) {
                if (!$to_user) {
                    $to_user = $from_user;
                }

                if (is_numeric($to_user) || is_string($to_user)) {
                    $to_user = EntitiesFactory::build((int) $to_user);
                }

                if (!$to_user) {
                    continue;
                }

                $notification = (new Entities\Notification())
                    ->setTo($to_user)
                    ->setEntity($params['entity'])
                    ->setFrom($from_user)
                    ->setNotificationView($params['notification_view'])
                    ->setDescription(isset($params['description']) ? $params['description'] : '')
                    ->setOwner($to_user ? $to_user : static::getCurrentUser())
                    ->setParams($params['params'])
                    ->setTimeCreated(time());

                if (!$params['dry']) {
                    $notification->save();

                    Push::_()->queue([
                        'uri' => 'notification',
                        'from' => $from_user,
                        'to' => $to_user,
                        'params' => $params
                    ]);
                }

                $notifications[] = $notification;
            }

            $event->setResponse($notifications);

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
                $message = $params->description;
            }

            if ($params->title) {
                $message .= $params->title;
            }

            $remind_owner_username = null;

            if ($type == 'activity' && $params->remind_object) {
                $remind_owner = $params->getOwnerEntity();

                if ($remind_owner) {
                    $remind_owner_username = $remind_owner->username;
                }
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

                    if ($user->guid) {
                        $to[] = $user->guid;
                    }
                }

                if ($to) {
                    Dispatcher::trigger('notification', 'all', [
                        'to' => $to,
                        'entity' => $params,
                        'notification_view' => 'tag',
                        'description' => $params->message,
                        'title' => $params->title
                    ]);
                }
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
}
