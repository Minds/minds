<?php
/**
 * Cassandra Repository.
 */

namespace Minds\Core\Notification;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Cassandra\Bigint;
use Cassandra\Timeuuid;
use Cassandra\Timestamp;
use Minds\Common\Urn;
use Minds\Core\Data\Cassandra\Prepared;

class CassandraRepository
{
    const NOTIFICATION_TTL = ((60 * 60) * 24) * 30; // 30 days

    /** @var $cql */
    private $cql;

    public function __construct($cql = null, $urn = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->urn = $urn ?: new Urn;
    }

    /**
     * Get a list of notifications.
     */
    public function getList($opts)
    {
        $opts = array_merge([
            'to_guid' => null,
            'type_group' => null,
            'uuid' => null,
            'offset' => null,
            'limit' => 12,
        ], $opts);

        if (!$opts['to_guid']) {
            throw new \Exception('to_guid must be provided');
        }

        $statement = "SELECT * FROM notifications
            WHERE to_guid = ?";
        $values = [
            new Bigint($opts['to_guid']),
        ];

        if ($opts['uuid']) {
            $statement .= " AND uuid = ?";
            $values[] = new Timeuuid($opts['uuid']);
        }

        if ($opts['type_group']) {
            $statement = "SELECT * FROM notifications_by_type_group 
                WHERE to_guid = ? 
                AND type_group = ?";
            $values[] = $opts['type_group'];
        }

        $query = new Prepared\Custom();
        $query->query($statement, $values);
        $query->setOpts([
            'page_size' => $opts['limit'],
            'paging_state_token' => base64_decode($opts['offset']),
        ]);

        try {
            $result = $this->cql->request($query);
        } catch (\Exception $e) {
            return false;
        }

        if (!$result) {
            return false;
        }

        $response = new Response();
        foreach ($result as $row) {
            $notification = new Notification();
            $notification->setUuid($row['uuid']->uuid() ?: null)
                ->setToGuid($row['to_guid'] ? (int) $row['to_guid']->value(): null)
                ->setFromGuid($row['from_guid'] ? (int) $row['from_guid']->value(): null)
                ->setEntityGuid((string) $row['entity_guid']) // REMOVE ONCE FULLY ON CASSANDRA
                ->setEntityUrn($row['entity_urn'])
                ->setCreatedTimestamp($row['created_timestamp'] ? $row['created_timestamp']->time() : null)
                ->setReadTimestamp($row['read_timestamp'] ? $row['read_timestamp']->time() : null)
                ->setType($row['type'])
                ->setData(json_decode($row['data'], true));
            $response[] = $notification;
        }
        $response->setPagingToken(base64_encode($result->pagingStateToken()));

        return $response;
    }

    /**
     * Get a single notification.
     * @param $urn
     * @return Notification
     */
    public function get($urn)
    {
        list ($to_guid, $uuid) = explode('-', $this->urn->setUrn($urn)->getNss(), 2);

        $response = $this->getList([
            'to_guid' => $to_guid,
            'uuid' => $uuid,
            'limit' => 1,
        ]);

        return $response[0];
    }


    /**
     * Add a notification to the database.
     *
     * @param Notification $notification
     *
     * @return Notification|bool
     */
    public function add($notification)
    {
        if (!$notification->getUuid()) {
            $notification->setUuid((new Timeuuid())->uuid());
        }

        $statement = 'INSERT INTO notifications (
            to_guid,
            uuid,
            type,
            type_group,
            from_guid,
            entity_guid,
            entity_urn,
            created_timestamp,
            read_timestamp,
            data
            ) VALUES (?,?,?,?,?,?,?,?,?, ?)
            USING TTL ?';

        $values = [
            new Bigint($notification->getToGuid()),
            new Timeuuid($notification->getUuid()),
            (string) $notification->getType(),
            Manager::getGroupFromType($notification->getType()),
            new Bigint($notification->getFromGuid()),
            (string) $notification->getEntityGuid(), // REMOVE ONCE FULLY ON CASSANDRA
            (string) $notification->getEntityUrn(),
            new Timestamp($notification->getCreatedTimestamp() ?: time()),
            $notification->getReadTimestamp() ? new Timestamp($notification->getReadTimestamp()) : null,
            json_encode($notification->getData()),
            static::NOTIFICATION_TTL,
        ];

        $query = new Prepared\Custom();
        $query->query($statement, $values);

        try {
            $success = $this->cql->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return $notification->getUuid();
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
