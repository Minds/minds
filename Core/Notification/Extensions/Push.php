<?php
namespace Minds\Core\Notification\Extensions;

use Minds\Core;
use Minds\Core\Di\Di;
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
            'exchange' => Di::_()->get('Config')->get('queue')['exchange'],
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

        if ($notification['params']['notification_view'] == 'like' || $notification['params']['notification_view'] == 'downvote') {
            return false;
        }

        $entity = $notification['notification']->getEntity();

        $entity_guid = '';
        $entity_type = 'object';
        $child_guid = '';
        $parent_guid = '';

        if (method_exists($entity, 'getGuid')) {
            $entity_guid = $entity->getGuid();
        } elseif (isset($entity->guid)) {
            $entity_guid = $entity->guid;
        }

        if ($entity->type === 'comment') {
            $parent_guid = $entity->getEntityGuid();
        } elseif (isset($entity->parent_guid)) {
            $parent_guid = $entity->parent_guid;
        }

        if (isset($entity->entity_guid)) {
            $child_guid = $entity->entity_guid;
        }

        if (method_exists($entity, 'getType')) {
            $entity_type = $entity->getType();
        } elseif (isset($entity->type)) {
            $entity_type = $entity->type;
        }

        if (method_exists($entity, 'getSubtype') && $entity->getSubtype()) {
            $entity_type .= ':' . $entity->getSubtype();
        } elseif (isset($entity->subtype) && $entity->subtype) {
            $entity_type .= ':' . $entity->subtype;
        }

        if (!$entity_guid && isset($notification['params']['entity_guid'])) {
            $entity_guid = $notification['params']['entity_guid'];
            $child_guid = '';
            $entity_type = '';
            $parent_guid = '';
        }

        $push = [
            'user_guid' => $notification['to']->guid,
            'entity_guid' => $entity_guid,
            'child_guid' => $child_guid,
            'entity_type' => $entity_type,
            'parent_guid' => $parent_guid,
            'type' => $notification['params']['notification_view'],
            'uri' => $notification['uri'],
            'badge' => $notification['count']
        ];

        $from_user = EntitiesFactory::build($notification['from'], [ 'cache' => true]) ?:
            Core\Session::getLoggedInUser();

        $push['title'] = 'Minds';
        $push['message'] = static::buildNotificationMessage($notification, $from_user, $entity);
        $push['large_icon'] = static::getNotificationLargeIcon($notification, $from_user);
        $push['big_picture'] = static::getNotificationBigPicture($notification, $from_user, $entity);
        $push['group'] = static::getNotificaitonGroup($notification, $from_user, $entity);

        return QueueClient::build()
            ->setExchange($notification['exchange'])
            ->setQueue($notification['queue'])
            ->send($push);
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
     * Get the group for the notification
     * @param  array  $notification
     * @param  mixed  $from_user
     * @param  mixed  $entity
     * @return string
     */
    protected static function getNotificaitonGroup(array $notification = [], $from_user, $entity)
    {
        return $notification['uri'];
    }

    /**
     * Get the big picture for the notification
     * @param  array  $notification
     * @param  mixed  $from_user
     * @param  mixed  $entity
     * @return string
     */
    protected static function getNotificationBigPicture(array $notification = [], $from_user, $entity)
    {
        switch ($notification['params']['notification_view']) {
            case 'tag':
                if (!empty($entity->custom_data)) {
                    return $entity->custom_data[0]['src'];
                }
            default:
                return null;

        }
    }

    /**
     * Get the large icon for the notification
     * @param  array  $notification
     * @param  mixed  $from_user
     * @return string
     */
    protected static function getNotificationLargeIcon(array $notification = [], $from_user)
    {
        switch ($notification['params']['notification_view']) {
            case 'boost_request':
            case 'boost_accepted':
            case 'boost_rejected':
            case 'boost_revoked':
            case 'boost_completed':
                return null;
            default:
                return $from_user->getIconURL('medium');

        }
    }


    /**
     * Creates a human-readable notification message
     * @param  array  $notification
     * @param  mixed  $from_user
     * @param  mixed  $entity
     * @return string
     */
    protected static function buildNotificationMessage(array $notification = [], $from_user, $entity)
    {
        $message = '';

        if (!isset($notification['params']['notification_view'])) {
            return $message;
        }

        $title = htmlspecialchars_decode($entity->title);

        $name = $from_user->name;

        $isOwner = $notification['to']->getGuid() == $entity->owner_guid;
        $prefix = $isOwner ? 'your ': $entity->ownerObj['name']."'s ";
        $desc = ($entity->type == 'activity') ? 'activity': $entity->subtype;

        $boostDescription = $entity->title ?: $entity->name ?: ($entity->type !== 'user' ? 'post' : 'channel');

        switch ($notification['params']['notification_view']) {

            case 'comment':
                return sprintf('%s commented on %s', $name, $prefix.$desc);

            case 'like':
                switch ($entity->type) {
                    case 'comment':
                        $like = 'your comment';
                        break;
                    case 'activity':
                        $like = $title ?: 'your activity';
                        break;
                    case 'object':
                        $like = $title ?: 'your '.$entity->subtype;
                        break;
                }
                return sprintf('%s voted up %s', $name, $like);

            case 'tag':
                return sprintf('%s mentioned you in a %s', $name, ($entity->type == 'comment') ? 'comment' : 'post');

            case 'friends':
                return sprintf('%s subscribed to you', $name);

            case 'remind':
                return sprintf('%s reminded your %s', $name, $desc);

            case 'boost_gift':
                return sprintf('%s gifted you %d views', $name, $notification['params']['impressions']);

            case 'boost_request':
                return sprintf('%s has requested a boost of %d points', $name, $notification['params']['points']);

            case 'boost_accepted':
                return sprintf('%d views for %s were accepted', $notification['params']['impressions'], $boostDescription);

            case 'boost_rejected':
                return sprintf('Your boost request for %s was rejected', $boostDescription);

            case 'boost_revoked':
                return sprintf('You revoked the boost request for %s', $boostDescription);

            case 'boost_completed':
                return sprintf('%d/%d impressions were met for %s', $notification['params']['impressions'], $notification['params']['impressions'], $boostDescription);

            case 'group_invite':
                return sprintf('%s invited you to %s', $name, $notification['params']['group']['name']);

            case 'messenger_invite':
                return sprintf('@%s wants to chat with you!', $name);

            default:
                return "";
        }
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
