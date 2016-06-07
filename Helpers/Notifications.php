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
use Minds\Entities\Notification as NotificationEntity;

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
            'offset' => '',
            'filter' => ''
        ];

        $allowedFilters = [ 'tags', 'comments', 'boosts', 'groups', 'subscriptions', 'votes', 'reminds' ];
        $options = array_merge($defaults, $options);
        $filter = '';

        if ($options['filter'] && in_array($options['filter'], $allowedFilters)) {
            $filter = ":{$options['filter']}";
        }

        $args = [
            'reversed' => $options['reversed'],
            'limit' => $options['limit'],
            'offset' => $options['offset']
        ];

        $db = new Core\Data\Call('entities_by_time');

        $rows = $db->getRow('notifications:' . $options['user_guid'] . $filter, $args);

        if ($args['offset'] && $args['limit'] > 1 && $rows) {
            unset($rows[$args['offset']]);
        }

        // if ($filter && !$args['offset'] && !$rows) {
        //     return static::filterBackwardsPolyfill($db, $filter, $options);
        // }

        $rows = static::polyfillNotifications($rows);

        return NotificationFactory::buildFromArray($rows);
    }

    /**
     * Fetches last 150 legacy non-filtered database rows, parses the filter they belong to,
     * saves them onto database and returns the requested subset.
     * @param  Call   $db
     * @param  string $filter
     * @param  array  $options
     * @return array
     */
    protected static function filterBackwardsPolyfill($db, $filter = '', array $options = []) {

        if (!$filter || !isset($options['user_guid'])) {
            return [];
        }

        $rawRows = $db->getRow('notifications:' . $options['user_guid'], [
            'reversed' => true,
            'limit' => 150
        ]) ?: [];

        $rows = NotificationFactory::buildFromArray(static::polyfillNotifications($rawRows));
        $results = [];

        // [ 'tags', 'comments', 'boosts', 'groups', 'subscriptions', 'votes', 'reminds' ];
        foreach ($rows as $row) {
            $parsedFilter = static::parseFilter($row);

            if ($parsedFilter == $filter) {
                $results[] = $row;
            }
        }

        // Save this set
        foreach ($results as $row) {
            $row->setFilter($filter);
            $row->save();
        }

        // Return requested subset (there shouldn't be an offset specified)
        return array_slice($results, 0 - $options['limit']);
    }

    //TODO: error_log this (to check usage)
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

    public static function parseFilter(NotificationEntity $notification) {
        $filter = '';

        switch ($notification->getNotificationView()) {
            case 'friends':
            case 'missed_call':
            case 'welcome_chat':
            case 'welcome_discover':
                $filter = 'subscriptions';
                break;

            case 'group_invite':
            case 'group_kick':
            case 'group_activity':
                $filter = 'groups';
                break;

            case 'comment':
                $filter = 'comments';
                break;

            case 'like':
            case 'downvote':
                $filter = 'votes';
                break;

            case 'remind':
                $filter = 'reminds';
                break;

            case 'tag':
                $filter = 'tags';
                break;

            case 'boost_gift':
            case 'boost_submitted':
            case 'boost_submitted_p2p':
            case 'boost_request':
            case 'boost_rejected':
            case 'boost_accepted':
            case 'boost_completed':
            case 'boost_peer_request':
            case 'boost_peer_accepted':
            case 'boost_peer_rejected':
            case 'welcome_points':
            case 'welcome_boost':
                $filter = 'boosts';
                break;

            case 'custom_message':
                if (
                    $notification->getParams() &&
                    $notification->getParams()['message'] &&
                    strpos($notification->getParams()['message'], 'points as a daily login reward') !== false
                ) {
                    $filter = 'boosts';
                }
                break;
        }

        return $filter;
    }
}
