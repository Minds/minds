<?php
/**
 * Repository
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Cassandra\Bigint;
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
     * @throws \NotImplementedException
     */
    public function getList(array $opts = [])
    {
        throw new \NotImplementedException();
    }

    /**
     * @param Summon $summon
     * @return bool
     */
    public function add(Summon $summon)
    {
        $expires = time() + ((int) $summon->getTtl());

        $cql = "INSERT INTO moderation_summons (report_urn, jury_type, juror_guid, status, expires) VALUES (?, ?, ?, ?, ?) USING TTL ?";
        $values = [
            $summon->getReportUrn(),
            $summon->getJuryType(),
            new Bigint($summon->getJurorGuid()),
            $summon->getStatus(),
            $expires,
            (int) $summon->getTtl(),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * @param Summon $summon
     * @return bool
     */
    public function exists(Summon $summon)
    {
        $cql = "SELECT COUNT(*) as total FROM moderation_summons WHERE report_urn = ? AND jury_type = ? AND juror_guid = ?";
        $values = [
            $summon->getReportUrn(),
            $summon->getJuryType(),
            new Bigint($summon->getJurorGuid()),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $response = $this->db->request($prepared);

            return $response[0]['total'] > 0;
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * @param Summon $summon
     * @throws \NotImplementedException
     */
    public function delete(Summon $summon)
    {
        throw new \NotImplementedException();
    }
}
