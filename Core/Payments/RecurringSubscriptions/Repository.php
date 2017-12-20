<?php

/**
 * Recurring Subscriptions Repository
 *
 * @author emi
 */

namespace Minds\Core\Payments\RecurringSubscriptions;

use Cassandra\Decimal;
use Cassandra\Rows;
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
     * @param array $options
     * @return Rows
     */
    public function select(array $options = [])
    {
        $options = array_merge([
            'status' => null,
            'next_billing' => null
        ], $options);

        $template = "SELECT * FROM recurring_subscriptions";
        $values = [];

        $where = [];

        $allowFiltering = false;

        if ($options['status']) {
            $where[] = 'status = ?';
            $values[] = (string) $options['status'];
            $allowFiltering = true;
        }

        if ($options['next_billing']) {
            if ($options['next_billing'] instanceof \DateTime) {
                $options['next_billing'] = $options['next_billing']->getTimestamp();
            }

            $where[] = 'next_billing <= ?';
            $values[] = new Timestamp((int) $options['next_billing']);
            $allowFiltering = true;
        }

        if ($where) {
            $template .= " WHERE " . implode(' AND ', $where);
        }

        if ($allowFiltering) {
            $template .= " ALLOW FILTERING";
        }

        $query = new Custom();
        $query->query($template, $values);

        return $this->cql->request($query);
    }

    /**
     * @param string $type
     * @param string $payment_method
     * @param integer|string $entity_guid
     * @param integer|string $user_guid
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function upsert($type, $payment_method, $entity_guid, $user_guid, array $data)
    {
        if (!$type) {
            throw new \Exception('Type is required');
        }

        if (!$payment_method) {
            throw new \Exception('Payment Method is required');
        }

        if (!$entity_guid) {
            throw new \Exception('Entity GUID is required');
        }

        if (!$user_guid) {
            throw new \Exception('User GUID is required');
        }

        if (!$data) {
            throw new \Exception('Data set is required');
        }

        $extraFields = filter_var_array($data, [
            'subscription_id' => true,
            'recurring' => true,
            'amount' => true,
            'status' => true,
            'last_billing' => true,
            'next_billing' => true,
        ], false);

        if (isset($extraFields['amount'])) {
            $extraFields['amount'] = new Decimal((string) $data['amount']);
        }

        if (isset($extraFields['last_billing'])) {
            $extraFields['last_billing'] = $data['last_billing'] ? new Timestamp($data['last_billing']) : null;
        }

        if (isset($extraFields['next_billing'])) {
            $extraFields['next_billing'] = $data['next_billing'] ? new Timestamp($data['next_billing']) : null;
        }

        $extraFieldNames = ($extraFields ? ', ' : '') . implode(', ', array_keys($extraFields));
        $extraFieldValues = array_values($extraFields);
        $extraFieldValuePlaceholders = str_repeat(', ?', count($extraFields));

        $query = new Custom();
        $query->query("INSERT INTO recurring_subscriptions (
            type,
            payment_method,
            entity_guid,
            user_guid
            {$extraFieldNames}
        ) VALUES (?, ?, ?, ? {$extraFieldValuePlaceholders})", array_merge([
            (string) $type,
            (string) $payment_method,
            new Varint($entity_guid),
            new Varint($user_guid)
        ], $extraFieldValues));

        $result = $this->cql->request($query);

        return (bool) $result;
    }

    /**
     * @param string $type
     * @param string $payment_method
     * @param integer|string $entity_guid
     * @param integer|string $user_guid
     * @return bool
     * @throws \Exception
     */
    public function delete($type, $payment_method, $entity_guid, $user_guid)
    {
        if (!$type) {
            throw new \Exception('Type is required');
        }

        if (!$payment_method) {
            throw new \Exception('Payment Method is required');
        }

        if (!$entity_guid) {
            throw new \Exception('Entity GUID is required');
        }

        if (!$user_guid) {
            throw new \Exception('User GUID is required');
        }

        $query = new Custom();
        $query->query("DELETE FROM recurring_subscriptions WHERE
          type = ? AND
          payment_method = ? AND
          entity_guid = ? AND
          user_guid = ?
        ", [
            (string) $type,
            (string) $payment_method,
            new Varint($entity_guid),
            new Varint($user_guid)
        ]);

        $result = $this->cql->request($query);

        return (bool) $result;
    }
}
