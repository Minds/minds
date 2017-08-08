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
     * Returns how much money or points a user has sent through wire in a given time
     * @param $sender_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getSumBySender($sender_guid, $method, $timestamp = null)
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$timestamp) {
            $timestamp =  mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum FROM wire_by_sender
          WHERE sender_guid=?
          AND method=?
          AND timestamp >= ? ALLOW FILTERING", [
            new \Cassandra\Varint($sender_guid),
            $method,
            new \Cassandra\Timestamp($timestamp)
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            return $result[0]['amount_sum']->value();
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * Returns how much money or points a user has sent through wire in a given time to a given user
     * @param $sender_guid
     * @param $receiver_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getSumBySenderForReceiver($sender_guid, $receiver_guid, $method, $timestamp = null)
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$timestamp) {
            $timestamp =  mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum FROM wire_by_sender
          WHERE sender_guid=?
          AND receiver_guid=?
          AND method=?
          AND timestamp >= ?", [
            new \Cassandra\Varint($sender_guid),
            new \Cassandra\Varint($receiver_guid),
            $method,
            new \Cassandra\Timestamp($timestamp)
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            return $result[0]['amount_sum']->value();
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     *  Returns how much money or points a user has received through wire
     * @param $receiver_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getSumByReceiver($receiver_guid, $method, $timestamp)
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$timestamp) {
            $timestamp =  mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum FROM wire
          WHERE receiver_guid=?
          AND method=?
          AND timestamp >= ?", [
            new \Cassandra\Varint($receiver_guid),
            $method,
            new \Cassandra\Timestamp($timestamp)
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            return $result[0]['amount_sum']->value();
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
    public function getSumByEntity($entity_guid, $method)
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$timestamp) {
            $timestamp =  '2000-01-01';
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum FROM wire_by_entity
          WHERE entity_guid=?
          AND method=?", [
            new \Cassandra\Varint($entity_guid),
            $method
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            return $result[0]['amount_sum']->value();
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
                    ->setMethod($row['method'])
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
        } catch (\Exception $e) {
        }
    }
}
