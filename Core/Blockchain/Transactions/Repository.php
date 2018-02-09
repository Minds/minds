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
        $template = "INSERT INTO blockchain_transactions (
            user_guid,
            wallet_address,
            timestamp,
            tx,
            contract,
            amount,
            completed,
            data
            ) 
            VALUES (?,?,?,?,?,?,?,?)";
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
            'contract' => null,
            'timestamp' => [
                'gte' => null,
                'lte' => null,
            ],
            'limit' => 12,
            'offset' => null,
            'allowFiltering' => false,
        ], $options);

        $cql = "SELECT * from blockchain_transactions";
        $where = [];
        $values = [];

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

        if ($options['contract']) {
            $where[] = 'contract = ?';
            $values[] = $options['contract'];
            $options['allowFiltering'] = true;
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
            'paging_state_token' => base64_decode($options['offset'])
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
                ->setAmount((double) $row['amount'])
                ->setCompleted((bool) $row['completed'])
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

        $cql = "SELECT * from blockchain_transactions_by_tx WHERE tx = ? AND user_guid = ?";
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
            ->setAmount((double) $row['amount'])
            ->setCompleted((bool) $row['completed'])
            ->setData(json_decode($row['data'], true));
            
        return $transaction;

    }

}
