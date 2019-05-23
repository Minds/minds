<?php
/**
 * Repository
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Cassandra\Bigint;
use Exception;
use Generator;
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
     * @return Generator
     * @yields Summons[]
     * @throws Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'report_urn' => null,
            'jury_type' => null,
            'juror_guid' => null,
            'limit' => 10000,
            'offset' => null,
        ], $opts);

        if (!$opts['report_urn']) {
            throw new Exception('Invalid Report URN');
        }

        if (!$opts['jury_type']) {
            throw new Exception('Invalid Jury type');
        }

        $cql = "SELECT * FROM moderation_summons WHERE report_urn = ? AND jury_type = ?";
        $values = [
            $opts['report_urn'],
            $opts['jury_type'],
        ];
        $cqlOpts = [];

        if ($opts['juror_guid']) {
            $cql .= " AND juror_guid = ?";
            $values[] = new Bigint($opts['juror_guid']);
        }

        if ($opts['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($opts['offset']);
        }

        if ($opts['limit']) {
            $cqlOpts['page_size'] = (int) $opts['limit'];
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);
        $prepared->setOpts($cqlOpts);

        try {
            $rows = $this->db->request($prepared);

            foreach ($rows as $row) {
                $summons = new Summons();
                $summons
                    ->setReportUrn($row['report_urn'])
                    ->setJuryType($row['jury_type'])
                    ->setJurorGuid($row['juror_guid']->toInt())
                    ->setStatus($row['status'])
                    ->setTtl($row['expires'] - time());

                yield $summons;
            }
        } catch (Exception $e) {
            error_log($e);
            return [];
        }
    }

    /**
     * @param Summons $summons
     * @return bool
     */
    public function add(Summons $summons)
    {
        $expires = time() + ((int) $summons->getTtl());

        $cql = "INSERT INTO moderation_summons (report_urn, jury_type, juror_guid, status, expires) VALUES (?, ?, ?, ?, ?) USING TTL ?";
        $values = [
            $summons->getReportUrn(),
            $summons->getJuryType(),
            new Bigint($summons->getJurorGuid()),
            $summons->getStatus(),
            $expires,
            (int) $summons->getTtl(),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * @param Summons $summons
     * @return bool
     */
    public function exists(Summons $summons)
    {
        $cql = "SELECT COUNT(*) as total FROM moderation_summons WHERE report_urn = ? AND jury_type = ? AND juror_guid = ?";
        $values = [
            $summons->getReportUrn(),
            $summons->getJuryType(),
            new Bigint($summons->getJurorGuid()),
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $response = $this->db->request($prepared);

            return $response[0]['total'] > 0;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * @param Summons $summons
     * @return bool
     * @throws Exception
     */
    public function delete(Summons $summons)
    {
        return $this->deleteAll([
            'report_urn' => $summons->getReportUrn(),
            'jury_type' => $summons->getJuryType(),
            'juror_guid' => $summons->getJurorGuid(),
        ]);
    }

    /**
     * @param array $opts
     * @return bool
     * @throws Exception
     * @yields array
     */
    public function deleteAll(array $opts = [])
    {
        $opts = array_merge([
            'report_urn' => null,
            'jury_type' => null,
            'juror_guid' => null,
        ], $opts);

        if (!$opts['report_urn']) {
            throw new Exception('Invalid Report URN');
        }

        if (!$opts['jury_type']) {
            throw new Exception('Invalid Jury type');
        }

        $cql = "DELETE FROM moderation_summons WHERE report_urn = ? AND jury_type = ?";
        $values = [
            $opts['report_urn'],
            $opts['jury_type'],
        ];

        if ($opts['juror_guid']) {
            $cql .= " AND juror_guid = ?";
            $values[] = new Bigint($opts['juror_guid']);
        }

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            return (bool) $this->db->request($prepared, true);
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
}
