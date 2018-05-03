<?php

/**
 * Subscriptions Repository
 *
 * @author emi / mark
 */

namespace Minds\Core\Payments\Subscriptions;

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
     * @param array $options
     * @return Subscription[]
     */
    public function getList(array $options = [])
    {
        $options = array_merge([
            'plan_id' => null,
            'payment_method' => null,
            'status' => null,
            'next_billing' => null,
            'user_guid' => null,
            'limit' => null,
            'offset' => null,
            'plan_id' => null,
            'entity_guid' => null,
        ], $options);

        $cqlOpts = [];

        $template = "SELECT * FROM subscriptions";
        $where = [];
        $values = [];

        if ($options['user_guid']) {
            $template = "SELECT * FROM subscriptions_by_user_guid";
            $where = [ 'user_guid = ?' ];
            $values = [ new Varint($options['user_guid']) ];
        }

        $allowFiltering = false;

        if ($options['plan_id']) {
            $where[] = 'plan_id = ?';
            $values[] = $options['plan_id'];
        }

        if ($options['payment_method']) {
            $where[] = 'payment_method = ?';
            $values[] = $options['payment_method'];
        }

        if ($options['entity_guid']) {
            $where[] = 'entity_guid = ?';
            $values[] = new Varint($options['entity_guid']);
            $allowFiltering = true;
        }

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

        if ($options['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($options['offset']);
        }

        if ($options['limit']) {
            $cqlOpts['page_size'] = (int) $options['limit'];
        }

        $query = new Custom();
        $query->query($template, $values);
        $query->setOpts($cqlOpts);

        $result = $this->cql->request($query);

        if (!$result) {
            return false;
        }

        $subscriptions = [];

        foreach ($result as $row) {
            $subscription = new Subscription();
            $subscription->setId($row['subscription_id'])
                ->setPlanId($row['plan_id'])
                ->setPaymentMethod($row['payment_method'])
                ->setEntity((string) $row['entity_guid'])
                ->setUser((string) $row['user_guid'])
                ->setAmount($row['amount']->toDouble())
                ->setInterval($row['interval'])
                ->setLastBilling($row['last_billing']->time())
                ->setNextBilling($row['next_billing']->time())
                ->setStatus($row['status']);
            $subscriptions[] = $subscription;
        }

        return $subscriptions;
    }

    /**
     * @param string $id
     * @return Subscription|false
     * @throws \Exception
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Exception('Subscription ID is required');
        }

        $query = new Custom();
        $query->query("SELECT * from subscriptions_by_id WHERE
          subscription_id = ?
        ", [
            (string) $id,
        ]);

        $result = $this->cql->request($query);

        if (!isset($result[0])) {
            return false;
        }

        $row = $result[0];

        $subscription = new Subscription();
        $subscription->setId($row['subscription_id'])
            ->setPlanId($row['plan_id'])
            ->setPaymentMethod($row['payment_method'])
            ->setEntity((string) $row['entity_guid'])
            ->setUser((string) $row['user_guid'])
            ->setAmount($row['amount']->toDouble())
            ->setInterval($row['interval'])
            ->setLastBilling($row['last_billing']->time())
            ->setNextBilling($row['next_billing']->time())
            ->setStatus($row['status']);

        return $subscription;
    }

    /**
     * @param Subscription $subscription
     * @return bool
     * @throws \Exception
     */
    public function add($subscription)
    {
        $query = new Custom();
        $query->query("INSERT INTO subscriptions (
            subscription_id,
            plan_id,
            payment_method,
            amount,
            entity_guid,
            user_guid,
            interval,
            status,
            last_billing,
            next_billing
        ) VALUES (?,?,?,?,?,?,?,?,?,?)",
        [
            $subscription->getId(),
            $subscription->getPlanId(),
            $subscription->getPaymentMethod(),
            new Decimal($subscription->getAmount()),
            new Varint($subscription->getEntity() ? $subscription->getEntity()->guid : 0),
            new Varint($subscription->getUser()->guid),
            $subscription->getInterval(),
            $subscription->getStatus(),
            new Timestamp($subscription->getLastBilling()),
            new Timestamp($subscription->getNextBilling())
        ]);

        $result = $this->cql->request($query);

        return (bool) $result;
    }

    public function update($subscription)
    {
        return false;
    }

    /**
     * @param Subscription $subscription
     * @return bool
     * @throws \Exception
     */
    public function delete($subscription)
    {

        $query = new Custom();
        $query->query("DELETE FROM subscriptions
            WHERE subscription_id = ?
            AND plan_id = ?
            AND payment_method = ?
            AND entity_guid = ?
            AND user_guid = ?", [
                (string) $subscription->getId(),
                $subscription->getPlanId(),
                $subscription->getPaymentMethod(),
                new Varint($subscription->getEntity() ? $subscription->getEntity()->guid : 0),
                new Varint($subscription->getUser()->guid)
            ]);

        $result = $this->cql->request($query);
        return (bool) $result;
    }

}
