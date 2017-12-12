<?php

/**
 * Payments Repository
 *
 * @author emi
 */

namespace Minds\Core\Payments;

use Cassandra\Decimal;
use Cassandra\Timestamp;
use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Repository
{
    /** @var Client $cql */
    protected $cql;

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param $payment_id
     * @return bool
     * @throws \Exception
     */
    public function getByPaymentId($payment_id)
    {
        if (!$payment_id) {
            throw new \Exception('Payment ID is required');
        }
        
        $query = new Custom();
        $query->query("SELECT * from payments_by_payment_id WHERE payment_id = ? LIMIT 1", [
            (string) $payment_id
        ]);

        $result = $this->cql->request($query);

        if (!isset($result[0])) {
            return false;
        }

        return $result[0];
    }

    /**
     * @param string $type
     * @param integer|string $user_guid
     * @param integer $time_created
     * @param string $payment_id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function upsert($type, $user_guid, $time_created, $payment_id, array $data)
    {
        if (!$type) {
            throw new \Exception('Type is required');
        }

        if (!$user_guid) {
            throw new \Exception('User GUID is required');
        }

        if (!$time_created) {
            throw new \Exception('Time Created is required');
        }

        if (!$payment_id) {
            throw new \Exception('Payment ID is required');
        }

        if (!$data) {
            throw new \Exception('Data set is required');
        }

        $extraFields = filter_var_array($data, [
            'subscription_id' => true,
            'payment_method' => true,
            'amount' => true,
            'description' => true,
            'status' => true,
        ], false);

        if (isset($extraFields['amount'])) {
            $extraFields['amount'] = new Decimal((string) $data['amount']);
        }

        $extraFieldNames = ($extraFields ? ', ' : '') . implode(', ', array_keys($extraFields));
        $extraFieldValues = array_values($extraFields);
        $extraFieldValuePlaceholders = str_repeat(', ?', count($extraFields));

        $query = new Custom();
        $query->query("INSERT INTO payments (
            type,
            user_guid,
            time_created,
            payment_id
            {$extraFieldNames}
        ) VALUES (?, ?, ?, ? {$extraFieldValuePlaceholders})", array_merge([
            (string) $type,
            new Varint($user_guid),
            new Timestamp($time_created),
            (string)$payment_id
        ], $extraFieldValues));

        $result = $this->cql->request($query);

        return (bool) $result;
    }
}
