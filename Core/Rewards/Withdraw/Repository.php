<?php


namespace Minds\Core\Rewards\Withdraw;

use Cassandra;
use Cassandra\Varint;
use Cassandra\Decimal;
use Cassandra\Timestamp;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Rewards\Transactions;
use Minds\Entities\User;


class Repository
{
    /** @var Client */
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param Transaction[]|Transaction $transactions
     * @return $this
     */
    public function add($transactions)
    {
        if (!is_array($transactions)) {
            $transactions = [ $transactions ];
        }

        $queries = [];
        $template = "INSERT INTO withdrawals ( user_guid, timestamp, amount, tx, completed) VALUES (?,?,?,?)";
        foreach ($transactions as $transaction) {
            $queries[] = [
                'string' => $template,
                'values' => [
                    new Varint($transaction->getUserGuid()),
                    new Timestamp($transaction->getTimestamp()),
                    $transaction->getData()['amount'],
                    $transaction->getTx(),
                    $transaction->isCompleted()
                ]
            ];
        }

        $this->db->batchRequest($queries, Cassandra::BATCH_UNLOGGED);

        return $this;
    }

    public function getList($options)
    {
        $options = array_merge([
            'user_guid' => null,
            'from' => null,
            'to' => null,
            'completed' => null,
            'limit' => 12,
            'offset' => null
        ], $options);

        $cql = "SELECT * from withdrawals";
        $where = [];
        $values = [];

        if ($options['user_guid']) {
            $where[] = 'user_guid = ?';
            $values[] = new Varint($options['user_guid']);
        }

        if ($options['from']) {
            $where[] = 'timestamp >= ?';
            $values[] = new Timestamp($options['from']);
        }

        if ($options['to']) {
            $where[] = 'timestamp <= ?';
            $values[] = new Timestamp($options['to']);
        }

        if ($options['completed']) {
            $where[] = 'completed = ?';
            $values[] = (string) $options['completed'];
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

        $withdrawals = [];

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
            $withdrawal = [
                'user' => new User($row['user_guid']),
                'user_guid' => $row['user_guid'],
                'timestamp' => $row['timestamp'],
                'amount' => (double) $row['amount'],
                'tx' => $row['tx'],
                'completed' => (bool) $row['completed']
            ];
            $withdrawals[] = $withdrawal;
        }

        return [
            'withdrawals' => $withdrawals,
            'token' => $rows->pagingStateToken()
        ];
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