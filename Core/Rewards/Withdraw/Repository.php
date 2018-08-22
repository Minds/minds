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
use Minds\Core\Util\BigNumber;
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
    public function add($requests)
    {
        if (!is_array($requests)) {
            $requests = [ $requests ];
        }

        $queries = [];
        $template = "INSERT INTO withdrawals (user_guid, timestamp, amount, tx, completed, completed_tx) VALUES (?,?,?,?,?,?)";
        foreach ($requests as $request) {
            $queries[] = [
                'string' => $template,
                'values' => [
                    new Varint($request->getUserGuid()),
                    new Timestamp($request->getTimestamp()),
                    new Varint($request->getAmount()),
                    $request->getTx(),
                    (bool) $request->isCompleted(),
                    $request->getCompletedTx()
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
            'completed_tx' => null,
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

        try {
            $rows = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }

        if (!$rows) {
            return [];
        }

        $requests = [];

        foreach($rows as $row) {
            $request = new Request();
            $request->setUserGuid((string) $row['user_guid']->value());
            $request->setTimestamp($row['timestamp']->time());
            $request->setAmount((string) BigNumber::_($row['amount']));
            $request->setTx($row['tx']);
            $request->setCompleted((bool) $row['completed']);
            $request->setCompletedTx($row['completed_tx']);
            $requests[] = $request;
        }

        return [
            'withdrawals' => $requests,
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
