<?php
/**
 * SQL Repository.
 */

namespace Minds\Core\Notification;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;

class Repository
{
    /** @var $sql */
    private $sql;

    public function __construct($sql = null)
    {
        $this->sql = $sql ?: Di::_()->get('Database\PDO');
    }

    /**
     * Get a single notification.
     *
     * @param $uuid
     *
     * @return Notification
     */
    public function get($uuid)
    {
        $query = 'SELECT * FROM notifications WHERE uuid = ?';

        $statement = $this->sql->prepare($query);

        $statement->execute([$uuid]);

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $notification = new Notification();

        foreach ($rows as $row) {
            $notification
                ->setUUID($row['uuid'])
                ->setToGuid($row['to_guid'])
                ->setFromGuid($row['from_guid'])
                ->setEntityGuid($row['entity_guid'])
                ->setCreatedTimestamp(strtotime($row['created_timestamp']))
                ->setReadTimestamp($row['read_timestamp'])
                ->setType($row['notification_type'])
                ->setData(json_decode($row['data'], true));
        }

        return $notification;
    }

    /**
     * Get a list of notifications.
     */
    public function getList($opts)
    {
        $opts = array_merge([
            'to_guid' => null,
            'offset' => 0,
            'limit' => 12,
            'type' => null,
        ], $opts);

        if (!$opts['to_guid']) {
            throw new \Exception('to_guid must be provided');
        }

        $params = [];

        /*$query = "
            SELECT uuid, to_guid, from_guid, entity_guid, notification_type,
                   created_timestamp, read_timestamp, data
                   FROM notifications";

        $join = "
            INNER JOIN (
                    SELECT batch_id FROM notification_batches
                    WHERE user_guid=?
                ) as ns
                ON (notifications.batch_id=ns.batch_id)";

        $joinParams = [
            (int) $opts['to_guid'],
        ];*/

        $union = '
            SELECT uuid, to_guid, from_guid, entity_guid, notification_type,
                created_timestamp, read_timestamp, data
            FROM notifications
            WHERE to_guid = ?';

        $unionParams = [
            (int) $opts['to_guid'],
        ];

        if ($opts['types']) {
            $placeholders = implode(', ', array_fill(0, count($opts['types']), '?'));
            //$join .= " AND notification_type IN ({$placeholders})";
            $union .= " AND notification_type IN ({$placeholders})";
            //$joinParams = array_merge($joinParams, $opts['types']);
            $unionParams = array_merge($unionParams, $opts['types']);
        }

        //$query .= $join . " UNION ALL " . $union;
        //$params = array_merge([], $joinParams, $unionParams);
        $query = $union;
        $params = $unionParams;

        $query .= ' ORDER BY created_timestamp DESC
                      LIMIT ? OFFSET ?';

        $params[] = (int) $opts['limit'];
        $params[] = (int) $opts['offset'];

        $statement = $this->sql->prepare($query);

        $statement->execute($params);

        $response = new Response();
        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $notification = new Notification();
            $notification->setUUID($row['uuid'])
                ->setToGuid($row['to_guid'])
                ->setFromGuid($row['from_guid'])
                ->setEntityGuid($row['entity_guid'])
                ->setCreatedTimestamp(strtotime($row['created_timestamp']))
                ->setReadTimestamp($row['read_timestamp'])
                ->setType($row['notification_type'])
                ->setData(json_decode($row['data'], true));
            $response[] = $notification;
        }
        $response->setPagingToken((int) $opts['offset'] + (int) $opts['limit']);

        return $response;
    }

    /**
     * Add a notification to the database.
     *
     * @param Notification[] $notifications
     *
     * @return Notification|bool
     */
    public function add($notifications)
    {
        if (!is_array($notifications)) {
            $notifications = [$notifications];
        }

        $query = 'INSERT INTO notifications (
            uuid,
            to_guid,
            from_guid,
            entity_guid,
            notification_type,
            data,
            batch_id
            ) VALUES ';

        $values = [];
        foreach ($notifications as $notification) {
            $values = array_merge($values, [
                $notification->getUuid(),
                $notification->getToGuid(),
                $notification->getFromGuid(),
                $notification->getEntityGuid(),
                $notification->getType(),
                json_encode($notification->getData()),
                (string) $notification->getBatchId(),
            ]);
        }

        $query .= implode(',', array_fill(0, count($notifications), '(?,?,?,?,?,?,?)'));

        $query .= ' RETURNING UUID';

        $statement = $this->sql->prepare($query);

        if ($statement->execute($values)) {
            return $statement->fetch(\PDO::FETCH_ASSOC)['uuid'];
        }

        return false;
    }

    // TODO
    public function update($notification, $fields)
    {
    }

    // TODO
    public function delete($uuid)
    {
    }
}
