<?php
/**
 * Notifications helper functions
 */

namespace Minds\Helpers;

use Minds\Core;
use Minds\Core\Events;
use Minds\Core\Notification\Factory as NotificationFactory;
use Minds\Entities\Factory;
use Minds\Helpers\Counters;

class Notifications
{
    use \Minds\Traits\CurrentUser;

    /**
     * Gets the notifications counter value for an user
     * @param  Entity $user    User. Use `null` to get the current one.
     * @param  array  $options
     * @return int
     */
    public static function getCount($user = null, array $options = [])
    {
        $user = Factory::build($user ?: static::getCurrentUser(), [
            'cache' => true
        ]);

        // FIXME: [emi] In legacy code this is defaulted to true, but the operation is commented out
        $cache = isset($options['cache']) ? $options['cache'] : false;

        if ($cache) {
            return $user->notifications_count;
        }

        return Counters::get($user, 'notifications:count', false);
    }

    /**
     * Updates the notifications counter value for an user
     * @param  User   $user
     * @return null
     */
    public static function increaseCounter($user = null)
    {
        $user = Factory::build($user ?: static::getCurrentUser(), [
            'cache' => true
        ]);

        try {
            if ($user) {
                elgg_set_ignore_access(true);

                $user->notifications_count++;
                $user->save();

                elgg_set_ignore_access(false);
            }
        } catch (Exception $e) {
            // NOOP
        }

        Counters::increment($user, 'notifications:count');
    }

    /**
     * Sets the notifications counter value to 0 for an user
     * @param  User   $user
     * @return null
     */
    public static function resetCounter($user = null)
    {
        $user = Factory::build($user ?: static::getCurrentUser(), [
            'cache' => true
        ]);

        try {
            if ($user) {
                elgg_set_ignore_access(true);

                $user->notifications_count = 0;
                $user->save();

                elgg_set_ignore_access(false);
            }
        } catch (Exception $e) {
            // NOOP
        }

        Counters::clear($user, 'notifications:count');
    }

    // -------- Reading

    /**
     * Gets notifications from database
     * @param  array  $options
     * @return array
     */
    public static function get(array $options = [])
    {
        $defaults = [
            'user_guid' => Core\Session::getLoggedinUserGuid(),
            'reversed' => true,
            'limit' => 12,
            'offset' => ''
        ];

        $options = array_merge($defaults, $options);

        $args = [
            'reversed' => $options['reversed'],
            'limit' => $options['limit'],
            'offset' => $options['offset']
        ];

        $db = new Core\Data\Call('entities_by_time');

        $rows = $db->getRow('notifications:' . $options['user_guid'], $args);

        if ($args['offset']) {
            unset($rows[$args['offset']]);
        }

        $rows = static::polyfillNotifications($rows);

        return NotificationFactory::buildFromArray($rows);
    }

    /**
     * Handles legacy notification rows in database
     * @param  array &$rows
     * @return null
     */
    protected static function polyfillNotifications($rows)
    {
        $guids = [];

        // Pre-process (get all legacy notification references)
        foreach ($rows as $guid => $data) {
            if (!is_numeric($data) || $guid != (int) $data) {
                continue;
            }

            $guids[] = $guid;
        }

        if (!$guids) {
            return $rows;
        }

        // Fetch legacy notification rows
        $db = new Core\Data\Call('entities');
        $notifications = $db->getRows($guids);

        // Post-process (apply notification rows)
        foreach ($rows as $guid => $data) {
            if (!is_numeric($data) || !isset($notifications[$guid])) {
                continue;
            }

            $notification = $notifications[$guid];

            $rows[$guid] = [
                'type' => 'notification',
                'guid' => $guid,
                'notification_view' => $notification['notification_view'],
                'description' => $notification['description'],
                'read' => $notification['read'],
                'access_id' => $notification['access_id'],
                'params' => json_decode($notification['params'], true) ?: [],
                'time_created' => $notification['time_created'],
                'to' => $notification['to_guid'],
                'entity' => $notification['object_guid'],
                'from' => $notification['from_guid'],
                'owner' => $notification['owner_guid'],
            ];
        }

        return $rows;
    }
}
