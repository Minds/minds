<?php
/**
 * Notifications helper functions
 */

namespace Minds\Core\Notification;

use Minds\Core;
use Minds\Core\Notification\Factory as NotificationFactory;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Entities\Notification as NotificationEntity;

class Notifications
{
    use \Minds\Traits\CurrentUser;

    protected $db;
    protected $user;

    public function __construct($db = null)
    {
        $this->db = $db ?: new Core\Data\Call('entities_by_time');
        $this->user = Core\Session::getLoggedInUser();
    }

    /**
     * Set the user
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Gets the notifications counter value for current user
     * @param  Entity $user    User. Use `null` to get the current one.
     * @param  array  $options
     * @return int
     */
    public function getCount(array $options = [])
    {

        // FIXME: [emi] In legacy code this is defaulted to true, but the operation is commented out
        $cache = isset($options['cache']) ? $options['cache'] : false;

        if ($cache) {
            return $this->user->notifications_count;
        }

        return Counters::get($this->user, 'notifications:count', false);
    }

    /**
     * Updates the notifications counter value for an user
     * @param  User   $user
     * @return null
     */
    public function increaseCounter()
    {
        try {
            elgg_set_ignore_access(true);

            $this->user->notifications_count++;
            $this->user->save();

            elgg_set_ignore_access(false);
        } catch (Exception $e) {
            // NOOP
        }

        Counters::increment($this->user, 'notifications:count');
    }

    /**
     * Sets the notifications counter value to 0 for an user
     * @param  User   $user
     * @return null
     */
    public function resetCounter()
    {
        try {
            elgg_set_ignore_access(true);

            $this->user->notifications_count = 0;
            $this->user->save();

            elgg_set_ignore_access(false);
        } catch (Exception $e) {
            // NOOP
        }

        Counters::clear($this->user, 'notifications:count');
    }

    // -------- Reading

    /**
     * Gets notifications from database
     * @param  array  $options
     * @return array
     */
    public function getList(array $options = [])
    {
        $defaults = [
            'user_guid' => $this->user->guid,
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

        $rows = $this->db->getRow('notifications:' . $options['user_guid'] . $filter, $args) ?: [];

        if ($args['offset'] && $rows) {
            unset($rows[$args['offset']]);
        }

        // NOT USED (YET)
        // if ($filter && !$args['offset'] && !$rows) {
        //     return $this->filterBackwardsPolyfill($filter, $options);
        // }

        $rows = $this->polyfillNotifications($rows);

        return NotificationFactory::buildFromArray($rows);
    }

    /**
     * Gets a single notification from the database
     * @param  mixed GUID
     * @return array
     */
    public function getSingle($guid)
    {
        $rows = $this->db->getRow('notifications:' . $this->user->guid, [
            'limit' => 1,
            'offset' => $guid,
            'reversed' => false
        ]) ?: [];

        if (!$rows) {
            return false;
        }

        $rows = $this->polyfillNotifications($rows);

        if (!$rows) {
            return false;
        }

        return NotificationFactory::build(current($rows));
    }

    /**
     * === NOT USED (YET) ===
     * Fetches last 150 legacy non-filtered database rows, parses the filter they belong to,
     * saves them onto database and returns the requested subset.
     * @param  string $filter
     * @param  array  $options
     * @return array
     */
    protected function filterBackwardsPolyfill($filter = '', array $options = [])
    {
        if (!$filter || !isset($options['user_guid'])) {
            return [];
        }

        $rawRows = $this->db->getRow('notifications:' . $options['user_guid'], [
            'reversed' => true,
            'limit' => 150
        ]) ?: [];

        $rows = NotificationFactory::buildFromArray($this->polyfillNotifications($rawRows));
        $results = [];

        // [ 'tags', 'comments', 'boosts', 'groups', 'subscriptions', 'votes', 'reminds' ];
        foreach ($rows as $row) {
            $parsedFilter = $this->parseFilter($row);

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

    /**
     * Handles legacy notification rows in database (saved as string)
     * @param  array &$rows
     * @return null
     */
    protected function polyfillNotifications($rows)
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

        $rowCount = count($guids);
        error_log("[DEPRECATION] User {$this->user->guid} has {$rowCount} legacy notifications.", 0);

        // Fetch legacy notification rows
        $notifications = $this->db->getRows($guids);

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
