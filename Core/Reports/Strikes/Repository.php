<?php
/**
 * Strikes Repository
 */
namespace Minds\Core\Reports\Strikes;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Common\Urn;
use Minds\Common\Repository\Response;
use Cassandra\Timestamp;
use Cassandra\Bigint;
use Cassandra\Tinyint;
use Cassandra\Decimal;

class Repository
{
    /** @var Client $cql */
    private $cql;

    /** @var Urn $urn */
    private $urn;

    public function __construct($cql = null, $urn = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->urn = $urn ?: new Urn();
    }

    /**
     * List of strikes
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null,
            'reason_code' => null,
            'sub_reason_code' => null,
            'timestamp' => null,
            'from' => strtotime('-90 days'),
            'to' => time(),
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('User guid must be provided');
        }
    
        $statement = "SELECT * FROM moderation_strikes
            WHERE user_guid = ?";

        $values = [
            new Bigint($opts['user_guid']),
        ];

        if (isset($opts['reason_code'])) {
            $statement .= " AND reason_code = ?";
            $values[] = new Tinyint($opts['reason_code']);
        }

        if (isset($opts['sub_reason_code'])) {
            $statement .= " AND sub_reason_code = ?";
            $values[] = new Decimal($opts['sub_reason_code']);
        }

        if ($opts['timestamp']) {
            $statement .= " AND timestamp = ?";
            $values[] = new Timestamp($opts['timestamp']);
        }

        if ($opts['from']) {
            $statement .= " AND timestamp > ?";
            $values[] = new Timestamp($opts['from']);
        }

        if ($opts['to']) {
            $statement .= " AND timestamp < ?";
            $values[] = new Timestamp($opts['to']);
        }

        if (!isset($opts['reason_code']) && !isset($opts['sub_reason_code'])) {
            $statement .= " ALLOW FILTERING";
        }

        $prepared = new Prepared;
        $prepared->query($statement, $values);

        $results = $this->cql->request($prepared);

        $response = new Response;

        foreach ($results as $row) {
            $strike = new Strike();
            $strike
                ->setUserGuid($row['user_guid']->value())
                ->setTimestamp($row['timestamp']->time())
                ->setReasonCode((int) $row['reason_code']->value())
                ->setSubReasonCode((int) $row['sub_reason_code']->value())
                ->setReportUrn($row['report_urn']);
            $response[] = $strike;
        }

        return $response;
    }

    /**
     * Return a single strike
     * @return Strike
     */
    public function get($urn)
    {
        // Decode the urn
        $parts = explode('-', $this->urn->setUrn($urn)->getNss());

        $response = $this->getList([
            'user_guid' => $parts[0],
            'timestamp' => $parts[1],
            'reason_code' => $parts[2],
            'sub_reason_code' => $parts[3],
        ]);

        $strike = $response[0];

        return $strike;
    }

    /**
     * Strike
     * @param Strike $strike
     * @return bool
     */
    public function add(Strike $strike)
    {
        $statement = "INSERT INTO moderation_strikes
            (user_guid, timestamp, reason_code, sub_reason_code, report_urn)
            VALUES 
            (?, ?, ?, ?, ?)";
        $values = [
            new Bigint($strike->getUserGuid()),
            new Timestamp($strike->getTimestamp()),
            new Tinyint($strike->getReasonCode()),
            new Decimal($strike->getSubReasonCode()),
            $strike->getReportUrn(),
        ];

        $prepared = new Prepared();
        $prepared->query($statement, $values);
        return (bool) $this->cql->request($prepared);
    }

    public function update(Strike $strike, $fields = [])
    {

    }

    public function delete(Strike $strike)
    {
        $statement = "DELETE FROM moderation_strikes 
            WHERE user_guid = ?
            AND reason_code = ?
            AND sub_reason_code = ?
            AND timestamp = ?";

        $values = [
            new Bigint($strike->getUserGuid()),
            new Tinyint($strike->getReasonCode()),
            new Decimal($strike->getSubReasonCode()),
            new Timestamp($strike->getTimestamp()),
        ];

        $prepared = new Prepared();
        $prepared->query($statement, $values);
        return (bool) $this->cql->request($prepared);
    }

}
