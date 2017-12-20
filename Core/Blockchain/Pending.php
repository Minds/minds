<?php

/**
 * Blockchain pending transactions manager
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core;
use Minds\Core\Di\Di;

class Pending
{
    const PENDING_TTL = 30 * 24 * 60 * 60;

    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        $ttl = static::PENDING_TTL;

        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("INSERT INTO blockchain_pending (type, tx_id, sender_guid, data) VALUES (?, ?, ?, ?) USING TTL ?", [
            (string) $data['type'],
            (string) $data['tx_id'],
            new \Cassandra\Varint($data['sender_guid']),
            (string) json_encode($data['data']),
            $ttl
        ]);

        try {
            return (bool) $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $type
     * @param string $tx_id
     * @return array|false
     */
    public function get($type, $tx_id)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $query->query("SELECT * from blockchain_pending WHERE type = ? AND tx_id = ?", [
            (string) $type,
            (string) $tx_id
        ]);

        try {
            $result = $this->db->request($query);

            if (isset($result[0])) {
                $result = $result[0];
                $result['sender_guid'] = $result['sender_guid']->value();
                $result['data'] = json_decode($result['data'], true);

                return $result;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * @param string $type
     * @param string $tx_id
     * @return bool
     */
    public function delete($type, $tx_id)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("DELETE FROM blockchain_pending WHERE type = ? AND tx_id = ?",
            [
                (string) $type,
                (string) $tx_id
            ]);

        try {
            return (bool) $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }
    }
}
