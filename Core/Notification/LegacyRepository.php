<?php
namespace Minds\Core\Notification;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;

class LegacyRepository
{
    const NOTIFICATION_TTL = 30 * 24 * 60 * 60;

    protected $owner;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    public function setOwner($guid)
    {
        if (is_object($guid)) {
            $guid = $guid->guid;
        } elseif (is_array($guid)) {
            $guid = $guid['guid'];
        }

        $this->owner = $guid;

        return $this;
    }

    public function getAll($type = null, array $options = [])
    {
        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        $options = array_merge([
            'limit' => 12,
            'offset' => ''
        ], $options);

        $template = "SELECT * FROM notifications WHERE owner_guid = ?";
        $values = [ new Cassandra\Varint($this->owner) ];
        $allowFiltering = false;

        if ($type) {
            // TODO: Switch template to materialized view
            $template .= " AND type = ?";
            $values[] = (string) $type;
            $allowFiltering = true;
        }

        $template .= " ORDER BY guid DESC";

        if ($allowFiltering) {
            $template .= " ALLOW FILTERING";
        }

        $query = new Prepared\Custom();
        $query->query($template, $values);
        $query->setOpts([
            'page_size' => $options['limit'],
            'paging_state_token' => base64_decode($options['offset'])
            ]);

        $notifications = [];

        try {
            $result = $this->db->request($query);

            foreach ($result as $row) {
                $notification = new Notification();
                $data = json_decode($row['data'], true);
                
                $params = $data['params'];
                $params['description'] = $data['description'];

                $entityGuid = $data['entity']['guid'];

                if ($data['entity']['type'] == 'comment') {
                    $luid = json_decode(base64_decode($data['entity']['luid']), true);
                    $entityGuid = $luid['guid'];
                }

                $notification
                    ->setUUID((string) $row['guid'])
                    ->setToGuid((string) $row['owner_guid'])
                    ->setType($data['notification_view'])
                    ->setFromGuid($data['from']['guid'])
                    ->setEntityGuid($data['entity']['guid'] ?: $data['entity']['_guid'])
                    ->setCreatedTimestamp($data['time_created'])
                    ->setData($params);
                $notifications[] = $notification;
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return [
          'notifications' => $notifications,
          'token' => base64_encode($result->pagingStateToken())
        ];
    }

    public function getEntity($guid)
    {
        if (!$guid) {
            return false;
        }

        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        $template = "SELECT * FROM notifications WHERE owner_guid = ? AND guid = ? LIMIT ?";
        $values = [ new Cassandra\Varint($this->owner), new Cassandra\Varint($guid), 1 ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        $notification = false;

        try {
            $result = $this->db->request($query);

            if (isset($result[0]) && $result[0]) {
                $notification = new Entities\Notification();
                $notification->loadFromArray($result[0]['data']);
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return $notification;
    }

    public function store($data, $age = 0)
    {
        if (!isset($data['guid']) || !$data['guid']) {
            return false;
        }

        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        $ttl = static::NOTIFICATION_TTL - $age;

        if ($ttl < 0) {
            return false;
        }

        $template = "INSERT INTO notifications (owner_guid, guid, type, data) VALUES (?, ?, ?, ?) USING TTL ?";
        $values = [
            new Cassandra\Varint($this->owner),
            new Cassandra\Varint($data['guid']),
            isset($data['filter']) && $data['filter'] ? $data['filter'] : 'other',
            json_encode($data),
            $ttl
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }
        
        return $success;
    }

    public function delete($guid)
    {
        if (!$guid) {
            return false;
        }

        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        $template = "DELETE FROM notifications WHERE owner_guid = ? AND guid = ? LIMIT ?";
        $values = [ new Cassandra\Varint($this->owner), new Cassandra\Varint($guid), 1];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return (bool) $success;
    }

    public function count()
    {
        if (!$this->owner) {
            throw new \Exception('Should call to setOwner() first');
        }

        $template = "SELECT COUNT(*) FROM notifications WHERE owner_guid = ?";
        $values = [ new Cassandra\Varint($this->owner) ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $result = $this->db->request($query);
            $count = (int) $result[0]['count'];
        } catch (\Exception $e) {
            $count = 0;
        }

        return $count;
    }
}
