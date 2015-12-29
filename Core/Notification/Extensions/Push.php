<?php
namespace Minds\Core\Notification\Extensions;

use Minds\Core;
use Minds\Interfaces;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Core\Queue\Client as QueueClient;

class Push implements Interfaces\NotificationExtensionInterface
{
    /**
     * Singleton instance
     * @var Push
     */
    public static $_;

    /**
     * Sends data to the Push queue
     * @param  array  $notification
     * @return mixed
     */
    public function queue(array $notification = [])
    {
        $notification = array_merge([
            'exchange' => 'mindsqueue',
            'queue' => 'Push',
            'uri' => null,
            'to' => null,
            'from' => null,
            'params' => []
        ], $notification);

        // TODO: [emi] should I throw an \Exception?
        if (!$notification['uri'] || !$notification['to']) {
            return false;
        }

        if($notification['params']['notification_view'] == 'like' || $notification['params']['notification_view'] == 'downvote'){
            return false;
        }

        return QueueClient::build()
            ->setExchange($notification['exchange'])
            ->setQueue($notification['queue'])
            ->send([
                'user_guid' => $notification['to']->guid,
                'message' => static::buildNotificationMesage($notification),
                'uri' => $notification['uri']
            ]);
    }

    /**
     * [NOT USED]
     * @param  array  $notification
     * @return boolean
     */
    public function send(array $notification = [])
    {
        return false;
    }

    /**
     * [NOT USED]
     * @return boolean
     */
    public function run()
    {
        return false;
    }

    /**
     * Creates a human-readable notification message
     * @param  array  $notification
     * @return string
     */
    protected static function buildNotificationMesage(array $notification = [])
    {
        $from_user = EntitiesFactory::build($notification['from'], [ 'cache' => true]) ?:
          Core\Session::getLoggedInUser();

        $message = 'You have a notification';

        if (!isset($notification['params']['notification_view'])) {
            return $message;
        }

        $title = htmlspecialchars_decode($notification['params']['title']);
        $description = htmlspecialchars_decode($notification['params']['description']);

        $name = $from_user->name;

        switch ($notification['params']['notification_view']) {

            case 'comment':
                $message = sprintf('%s commented: %s', $name, $notification['params']['description']);
                break;

            case 'like':
                $message = sprintf('%s voted up %s', $name, $notification['params']['title']);
                break;

            case 'tag':
                $message = sprintf('%s mentioned you in a post: %s', $name, $notification['params']['description']);
                break;

            case 'remind':
                $message = sprintf('%s reminded %s', $name, $notification['params']['title']);
                break;

            case 'boost_gift':
                $message = sprintf('%s gifted you %d views', $name, $notification['params']['impressions']);
                break;

            case 'boost_request':
                $message = sprintf('%s has requested a boost of %d points', $name, $notification['params']['points']);
                break;

            case 'boost_accepted':
                $message = sprintf('%d views for %s were accepted', $notification['params']['impressions'], $notification['params']['title']);
                break;

            case 'boost_rejected':
                $message = sprintf('Your boost request for %s was rejected', $notification['params']['title']);
                break;

            case 'boost_completed':
                $message = sprintf('%d/%d impressions were met for %s', $notification['params']['impressions'], $notification['params']['impressions'], $notification['params']['title']);
                break;

        }

        return $message;
    }

    /**
     * Factory builder
     */
    public static function _()
    {
        if (!self::$_) {
            self::$_ = new self();
        }
        return self::$_;
    }
}
