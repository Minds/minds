<?php
/**
 * Created by Marcelo.
 * Date: 31/07/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use Minds\Entities\Wire;


class Repository
{
    private $db;
    private $config;

    public function __construct($db = null, $config = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param $sender_guid
     * @param $timestamp
     * @param $method
     * @return int
     */
    public function getSumBySender($sender_guid, $timestamp, $method)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) FROM wire
          WHERE sender_guid=?
          AND method=? 
          AND timestamp >= ?", [
            new \Cassandra\Varint($sender_guid),
            $timestamp,
            new \Cassandra\Timestamp($method)
        ]);

        try {
            $result = $this->db->request($query);
            return $result[0];

        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * @param $receiver_guid
     * @param $method
     * @return int
     */
    public function getSumByReceiver($receiver_guid, $method)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) FROM wire
          WHERE receiver_guid=?
          AND method=?", [
            new \Cassandra\Varint($receiver_guid),
            $method
        ]);

        try {
            $result = $this->db->request($query);
            return $result[0];

        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * @param $entity_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getSumByEntity($entity_guid, $method, $timestamp)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) FROM wire
          WHERE entity_guid=?
          AND method=? 
          AND timestamp >= ?", [
            new \Cassandra\Varint($entity_guid),
            $method,
            new \Cassandra\Timestamp($timestamp)
        ]);

        try {
            $result = $this->db->request($query);
            return $result[0];

        } catch (\Exception $e) {
            return -1;
        }
    }

    public function get($entity_guid, $receiver_guid, $method)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("SELECT * FROM wire where method=? AND entity_guid=? and receiver_guid=? ALLOW FILTERING",
            [
                $method,
                new \Cassandra\Varint($entity_guid),
                new \Cassandra\Varint($receiver_guid)
            ]);
        try {
            $result = $this->db->request($query);
            $wires = [];
            foreach ($result as $row) {
                $wire = new Wire();
                $wire->setFrom(new User($row['sender_guid']))
                    ->setTo(new User($row['receiver_guid']))
                    ->setTimeCreated($row['timestamp'])
                    ->setEntity(Core\Entities::get(['guid' => $row['entity_guid']])[0])
                    ->setRecurring($row['recurring'])
                    ->setAmount($row['amount'])
                    ->setActive($row['active']);
                $wires[] = $wire;
            }
            return $wires;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * when adding a new wire, if it's recurring then it should check for a pre-existing recurring wire for that user
     * and cancel it before creating the new one
     * @param Wire $wire
     */
    public function add(Wire $wire)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("INSERT INTO wire
          (receiver_guid, sender_guid, method, timestamp, entity_guid, wire_guid, amount, recurring, status)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                new \Cassandra\Varint($wire->getTo()->guid),
                new \Cassandra\Varint($wire->getFrom()->guid),
                $wire->getMethod(),
                new \Cassandra\Timestamp($wire->getTimeCreated()),
                new \Cassandra\Varint($wire->getEntity()->guid),
                new \Cassandra\Varint($wire->getGuid()),
                new \Cassandra\Decimal($wire->getAmount()),
                (boolean) $wire->isRecurring(),
                'success'
            ]);

        try {
            $result = $this->db->request($query);
        } catch (\Exception $e) { }
    }
}