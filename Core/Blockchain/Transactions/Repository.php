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
            contract,
            tx,
            user_guid,
            timestamp,
            completed,
            data
            ) 
            VALUES (?,?,?,?,?,?)";
        foreach ($transactions as $transaction) {
            $requests[] = [
                'string' => $template, 
                'values' => [
                    $transaction->getContract(),
                    $transaction->getTx(),                    
                    new Varint($transaction->getUserGuid()),
                    new Timestamp($transaction->getTimestamp()),
                    $transaction->isCompleted(),
                    json_encode($transaction->getData())
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
            'contract' => null,
            'limit' => 12,
            'offset' => null
        ], $options);

        $cql = "SELECT * from blockchain_transactions";
        $where = [];
        $values = [];

        if ($options['contract']) {
            $where[] = 'contract = ?';
            $values[] = $options['contract'];
        }

        if ($options['user_guid']) {
            $where[] = 'user_guid = ?';
            $values[] = new Varint($options['user_guid']);
        }

        if ($where) {
            $cql .= " WHERE " . implode(" AND ", $where);
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
                ->setContract($row['contract'])            
                ->setUserGuid((int) $row['user_guid'])
                ->setTimestamp((int) $row['timestamp'])
                ->setTx($row['tx'])
                ->setCompleted((bool) $row['completed'])
                ->setData(json_decode($row['data'], true));
                
            $transactions[] = $transaction;
        }

        return [
            'transactions' => $transactions,
            'token' => $rows->pagingStateToken()
        ];
    }

    public function get($tx)
    {

        $cql = "SELECT * from blockchain_transactions WHERE tx = ?";
        $values = [ (string) $tx ];

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
            ->setContract($row['contract'])            
            ->setUserGuid((int) $row['user_guid'])
            ->setTimestamp((int) $row['timestamp'])
            ->setTx($row['tx'])
            ->setCompleted((bool) $row['completed'])
            ->setData(json_decode($row['data'], true));
            
        return $transaction;

    }

    public function update($key, $guids)
    {
        // TODO: Implement update() method.
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }


}
