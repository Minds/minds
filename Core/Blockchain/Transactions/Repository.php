<?php
/**
 * Blockchain Transactions Repository
 */
namespace Minds\Core\Blockchain\Transactions;

use Cassandra;
use Cassandra\Varint;
use Cassandra\Decimal;
use Cassandra\Timestamp;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;

class Repository
{
    /** @var Client */
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    public function add($transactions)
    {
        if (!is_array($transactions)) {
            $transactions = [ $transactions ];
        }

        $requests = [];
        $template = "INSERT INTO blockchain_transactions_mainnet (
            user_guid,
            wallet_address,
            timestamp,
            tx,
            contract,
            amount,
            completed,
            failed,
            data
            ) 
            VALUES (?,?,?,?,?,?,?,?,?)";
        foreach ($transactions as $transaction) {
            $requests[] = [
                'string' => $template, 
                'values' => [
                    new Varint($transaction->getUserGuid()),
                    $transaction->getWalletAddress(),
                    new Timestamp($transaction->getTimestamp()),
                    $transaction->getTx(),
                    $transaction->getContract(),
                    new Varint($transaction->getAmount()),
                    $transaction->isCompleted(),
                    $transaction->isFailed(),
                    json_encode($transaction->getData()),
                ]
            ];
        }

        $this->db->batchRequest($requests, Cassandra::BATCH_UNLOGGED);

        return $this;
    }

    public function getList($options)
    {
        $options = array_merge([
            'user_guid' => null,
            'wallet_address' => null,
            'wallet_addresses' => null,
            'timestamp' => [
                'gte' => null,
                'lte' => null,
                'eq' => null,
            ],
            'tx' => null,
            'contract' => null,
            'limit' => 12,
            'offset' => null,
            'allowFiltering' => false,
        ], $options);

        $cql = "SELECT * from blockchain_transactions_mainnet";
        $where = [];
        $values = [];

        if ($options['wallet_address'] && $options['user_guid'] && ($options['timestamp']['gte'] || $options['timestamp']['lte'])) {
            $cql = "SELECT * from blockchain_transactions_mainnet_by_address";
        }

        if ($options['user_guid']) {
            $where[] = 'user_guid = ?';
            $values[] = new Varint($options['user_guid']);
        }

        if ($options['wallet_address']) {
            $where[] = 'wallet_address = ?';
            $values[] = $options['wallet_address'];
        }

        if ($options['wallet_addresses']) {
            $placeholders = implode(', ', array_fill(0, count($options['wallet_addresses']), '?'));
            $where[] = "wallet_address IN ({$placeholders})";
            $values = array_merge($values, array_map(function ($value) {
                return (string) $value;
            }, $options['wallet_addresses']));
        }

        if ($options['timestamp']['gte']) {
            $where[] = 'timestamp >= ?';
            $values[] = new Timestamp($options['timestamp']['gte']);
        }

        if ($options['timestamp']['lte']) {
            $where[] = 'timestamp <= ?';
            $values[] = new Timestamp($options['timestamp']['lte']);
        }

        if ($options['timestamp']['eq']) {
            $where[] = 'timestamp = ?';
            $values[] = new Timestamp($options['timestamp']['eq']);
        }

        if ($options['contract']) {
            $where[] = 'contract = ?';
            $values[] = $options['contract'];
            $options['allowFiltering'] = true;
        }

        if ($options['tx']) {
            $where[] = 'tx = ?';
            $values[] = $options['tx'];
        }

        if ($where) {
            $cql .= " WHERE " . implode(" AND ", $where);
        }

        if ($options['allowFiltering']) {
            $cql .= " ALLOW FILTERING";
        }

        $query = new Custom();
        $query->query($cql, $values);
        $query->setOpts([
            'page_size' => (int) $options['limit'],
            'paging_state_token' => base64_decode($options['offset']),
            'consistency' => \Cassandra::CONSISTENCY_ALL,
            'retry_policy' => new \Cassandra\RetryPolicy\Logging(new \Cassandra\RetryPolicy\DowngradingConsistency())
        ]);

        $transactions = [];

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return [];
        }

        if (!$rows) {
            return [];
        }

        foreach($rows as $row) {
            $transaction = new Transaction();
            $transaction
                ->setTx($row['tx'])
                ->setUserGuid((int) $row['user_guid'])
                ->setWalletAddress($row['wallet_address'])
                ->setTimestamp((int) $row['timestamp']->time())
                ->setContract($row['contract'])            
                ->setAmount((string) BigNumber::_($row['amount']))
                ->setCompleted((bool) $row['completed'])
                ->setFailed((bool) $row['failed'])
                ->setData(json_decode($row['data'], true));
                
            $transactions[] = $transaction;
        }
        
        return [
            'transactions' => $transactions,
            'token' => $rows->pagingStateToken()
        ];
    }

    public function get($user_guid, $tx)
    {

        $cql = "SELECT * from blockchain_transactions_mainnet_by_tx WHERE tx = ? AND user_guid = ?";
        $values = [ (string) $tx, new Varint($user_guid) ];

        $query = new Custom();
        $query->query($cql, $values);

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return [];
        }

        if (!$rows) {
            return [];
        }

        $row = $rows[0];

        $transaction = new Transaction();
        $transaction
            ->setTx($row['tx'])
            ->setUserGuid((int) $row['user_guid'])
            ->setWalletAddress($row['wallet_address'])
            ->setTimestamp((int) $row['timestamp']->time())
            ->setContract($row['contract'])            
            ->setAmount((string) BigNumber::_($row['amount']))
            ->setCompleted((bool) $row['completed'])
            ->setFailed((bool) $row['failed'])
            ->setData(json_decode($row['data'], true));
            
        return $transaction;

    }

    public function update($transaction, array $dirty = [])
    {
        $template = "UPDATE blockchain_transactions_mainnet";
        $values = [];
        $set = [];

        foreach ($dirty as $key) {
            $value = null;
            switch ($key) {
                case 'amount':
                    $value = new Cassandra\Varint($transaction->getAmount());
                    break;
                case 'failed':
                    $value = (bool) $transaction->isFailed();
                    break;
            }

            $set[] = "{$key} = ?";
            $values[] = $value;
        }

        if ($set) {
            $template .= ' SET ';
        }

        $template .= implode(", ", $set);

        $template .= " WHERE user_guid = ? AND timestamp = ?";
        $values[] = new Varint($transaction->getUserGuid());
        $values[] = new Timestamp($transaction->getTimestamp());

        $query = new Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return (bool) $success;
    }

    public function delete($user_guid, $timestamp, $wallet_address) {
        $cql = "DELETE FROM blockchain_transactions_mainnet where user_guid = ? AND timestamp = ?";
        $values = [ new Varint($user_guid), new Timestamp($timestamp) ];

        $query = new Custom();
        $query->query($cql, $values);

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

}
