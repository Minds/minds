<?php
/**
 * Repository.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Snapshots;

use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var CassandraClient */
    protected $db;

    /**
     * Repository constructor.
     * @param CassandraClient $db
     */
    public function __construct(
        $db = null
    )
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return \Generator
     * @throws \Exception
     * @yields Snapshot[]
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'type' => null,
        ], $opts);

        $cql = "SELECT * FROM user_snapshots WHERE user_guid = ?";
        $values = [
            new Varint($opts['user_guid']),
        ];

        if ($opts['type']) {
            $cql .= ' AND type = ?';
            $values[] = $opts['type'];
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $rows = $this->db->request($prepared);

            foreach ($rows as $row) {
                $snapshot = new Snapshot();
                $snapshot
                    ->setUserGuid((string) $row['user_guid'])
                    ->setType($row['type'])
                    ->setKey($row['key'])
                    ->setJsonData($row['json_data']);

                yield $snapshot;
            }
        } catch (\Exception $e) {
            error_log((string) $e);
            throw $e;
        }
    }

    /**
     * @param Snapshot $snapshot
     * @return bool
     */
    public function add(Snapshot $snapshot)
    {
        $cql = "INSERT INTO user_snapshots (user_guid, type, key, json_data) VALUES (?, ?, ?, ?)";
        $values = [
            new Varint($snapshot->getUserGuid()),
            $snapshot->getType(),
            $snapshot->getKey(),
            $snapshot->getJsonData(true),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $this->db->request($prepared, true);
            return true;
        } catch (\Exception $e) {
            error_log((string) $e);
            return false;
        }
    }

    /**
     * @param int|string $userGuid
     * @param string|null $type
     * @return bool
     */
    public function deleteAll($userGuid, $type = null)
    {
        $cql = "DELETE FROM user_snapshots WHERE user_guid = ?";
        $values = [
            new Varint($userGuid),
        ];

        if ($type) {
            $cql .= " AND type = ?";
            $values[] = $type;
        }
        
        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $this->db->request($prepared, true);
            return true;
        } catch (\Exception $e) {
            error_log((string) $e);
            return false;
        }
    }
}
