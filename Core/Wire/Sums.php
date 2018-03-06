<?php
/**
 * Sums for wires
 */
namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class Sums
{
    /** @var Client $db **/
    private $db;

    /** @var Config $config **/
    private $config;

    /** @var string method */
    private $method = 'tokens';

    /** @var string $receiver_guid **/
    private $receiver_guid;

    /** @var string $sender_guid **/
    private $sender_guid;

    /** @var int $from */
    private $from;

    /** @var string $entity_guid **/
    private $entity_guid;

    public function __construct($db = null, $config = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Sets the receiver
     * @param int $receiver
     * @return $this
     */
    public function setReceiver($receiver)
    {
        if (is_object($receiver)) {
            $this->receiver_guid = $receiver->guid;
        } else {
            $this->receiver_guid = $receiver;
        }
        return $this;
    }

    /**
     * Sets the sender
     * @param int $from
     * @return $this
     */
    public function setSender($sender)
    {
        if (is_object($sender)) {
            $this->sender_guid = $sender->guid;
        } else {
            $this->sender_guid = $sender;
        }
        return $this;
    }

    /**
     * Sets the entity
     * @param int $from
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity_guid = $entity;
        return $this;
    }

    /**
     * Timestamp to search from
     * @param int $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Returns how much money or points a user has sent through wire in a given time
     * @param $sender_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getSent()
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$this->from) {
            $this->from = mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        if ($this->receiver_guid) {
            $query->query("SELECT SUM(amount) as amount_sum, SUM(wei) as wei_sum FROM wire_by_sender
                WHERE sender_guid=?
                AND receiver_guid=?
                AND method=?
                AND timestamp >= ?", [
                    new \Cassandra\Varint($this->sender_guid),
                    new \Cassandra\Varint($this->receiver_guid),
                    $this->method,
                    new \Cassandra\Timestamp($this->from)
                ]);
        } else {
            $query->query("SELECT SUM(amount) as amount_sum FROM wire_by_sender
                WHERE sender_guid=?
                AND method=?
                AND timestamp >= ? ALLOW FILTERING", [
                    new \Cassandra\Varint($this->sender_guid),
                    $this->method,
                    new \Cassandra\Timestamp($this->from)
                ]);
        }

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            if ($this->method == 'tokens') {
                return (string) $result[0]['wei_sum'];
            } else {
                return (string) $result[0]['amount_sum'];
            }
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
    public function getReceived()
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$this->from) {
            $this->from = mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum, SUM(wei) as wei_sum FROM wire
          WHERE receiver_guid=?
          AND method=?
          AND timestamp >= ?", [
            new \Cassandra\Varint($this->receiver_guid),
            $this->method,
            new \Cassandra\Timestamp($this->from)
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            if ($this->method == 'tokens') {
                return (string) $result[0]['wei_sum'];
            } else {
                return (string) $result[0]['amount_sum'];
            }
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * Returns aggregates for a receiver
     * @param $receiver_guid
     * @param $method
     * @param $timestamp
     * @return array
     */
    public function getAggregates()
    {
        // if $timestamp isn't set, I set it to a default date prior to wire creation so the query sums everything
        if (!$this->from) {
            $this->from = mktime(0, 0, 0, 1, 1, 2000);
        }

        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT
          SUM(amount) as sum,
          SUM(wei) as wei_sum,
          COUNT(*) as count
          FROM wire
          WHERE receiver_guid=?
          AND method=?
          AND timestamp >= ?", [
            new \Cassandra\Varint($this->receiver_guid),
            $this->method,
            new \Cassandra\Timestamp($this->from)
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return [
                    'sum' => 0,
                    'count' => 0,
                    'avg' => 0
                ];
            }

            if ($this->method == 'tokens') {
                $sum = (string) $result[0]['wei_sum'];
            } else {
                $sum = (string) $result[0]['sum'];
            }
            $count = (string) BigNumber::_($result[0]['count']);

            return [
                'sum' => $sum,
                'count' => $count,
                'avg' => (string) BigNumber::_($sum)->div($count ?: 1)
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param $entity_guid
     * @param $method
     * @param $timestamp
     * @return int
     */
    public function getEntity()
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT SUM(amount) as amount_sum, SUM(wei) as wei_sum FROM wire_by_entity
          WHERE entity_guid=?
          AND method=?", [
            new \Cassandra\Varint($this->entity_guid),
            $this->method
        ]);

        try {
            $result = $this->db->request($query);
            if (!$result) {
                return 0;
            }
            return (string) BigNumber::_($result[0]['amount_sum'])->add($result[0]['wei_sum']);
        } catch (\Exception $e) {
            return -1;
        }
    }

}
