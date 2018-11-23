<?php
/**
 * SQL Repository
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

    // TODO
    public function get($uuid)
    {

    }

    /**
     * Get a list of notifications
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

        $query = "SELECT * FROM notifications
                      WHERE to_guid = ?";
        
        $params = [
            (int) $opts['to_guid'],
        ];

        if ($opts['types']) {
            $placeholders = implode(', ', array_fill(0, count($opts['types']), '?'));
            $query .= " AND notification_type IN ({$placeholders})";
            $params = array_merge($params, $opts['types']);
        }

        $query .= " ORDER BY created_timestamp DESC
                      LIMIT ? OFFSET ?";

        $params[] = (int) $opts['limit'];
        $params[] = (int) $opts['offset'];

        $statement = $this->sql->prepare($query);
        
        $statement->execute($params);

        $response =  new Response();
        foreach($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
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
     * Add a notification to the database
     * @param Notification[] $notification
     * @return bool
     */
    public function add($notifications)
    {
        if (!is_array($notifications)) {
            $notifications = [ $notifications ];
        }

        $query = "INSERT INTO notifications (
            to_guid,
            from_guid,
            entity_guid,
            notification_type,
            data
            ) VALUES ";

        $values = [];
        foreach ($notifications as $notification) {
            $values = array_merge($values, [
                $notification->getToGuid(),
                $notification->getFromGuid(),
                $notification->getEntityGuid(),
                $notification->getType(),
                json_encode($notification->getData()),
            ]);
        }

        $query .= implode(',', array_fill(0, count($values), '(?,?,?,?,?)'));

        $statement = $this->sql->prepare($query);

        return $statement->execute($values);
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
