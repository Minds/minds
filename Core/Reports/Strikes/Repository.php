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

class Repository
{
    /** @var Client $cql */
    private $cql;

    /** @var Urn $urn */
    private $urn;

    public function __construct($cql = null, $urn = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Client');
        $this->urn = $urn ?: new Urn();
    }

    /**
     * List of strikes
     * @param array $opts
     * @return Response
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

        if ($opts['reason_code']) {
            $statement .= " AND reason_code = ?";
            $values[] = (float) $opts['reason_code'];
        }

        if ($opts['sub_reason_code']) {
            $statement .= " AND sub_reason_code = ?";
            $values[] = (float) $opts['sub_reason_code'];
        }

        if ($opts['timestamp']) {
            $statement .= " AND timestamp = ?";
            $values[] = new Timestamp($opts['timestamp']);
        }

        if ($opts['from']) {
            $statement .= " AND timestamp > ?";
            $values[] = new Timestamp($opts['from'] * 1000);
        }

        if ($opts['to']) {
            $statement .= " AND timestamp < ?";
            $values[] = new Timestamp($opts['to'] * 1000);
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
                ->setReasonCode($row['reason_code']->toFloat())
                ->setSubReasonCode($row['sub_reason_code']->toFloat())
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
            (float) $strike->getReasonCode(),
            (float) $strike->getSubReasonCode(),
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

    }

}