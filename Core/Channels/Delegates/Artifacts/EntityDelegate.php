<?php
/**
 * EntityDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class EntityDelegate implements ArtifactsDelegateInterface
{
    /** @var Repository */
    protected $repository;

    /** @var CassandraClient */
    protected $db;

    /**
     * EntityDelegate constructor.
     * @param Repository $repository
     * @param CassandraClient $db
     */
    public function __construct(
        $repository = null,
        $db = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function snapshot($userGuid)
    {
        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function restore($userGuid)
    {
        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function hide($userGuid)
    {
        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function delete($userGuid)
    {
        $cql = "DELETE FROM entities WHERE key = ?";
        $values = [
            (string) $userGuid,
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $this->db->request($prepared, true);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
