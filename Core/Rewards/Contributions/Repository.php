<?php
namespace Minds\Core\Rewards\Contributions;

use Cassandra;
use Cassandra\Varint;
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

    public function add($contributions)
    {
        if (!is_array($contributions)) {
            $contributions = [ $contributions ];
        }

        $requests = [];
        $template = "INSERT INTO contributions (
            timestamp,
            user_guid,
            metric,
            amount,
            score
            ) 
            VALUES (?,?,?,?,?)";
        foreach ($contributions as $contribution) {
            $requests[] = [
                'string' => $template, 
                'values' => [
                    new Timestamp($contribution->getTimestamp() / 1000),                    
                    new Varint($contribution->getUser()->guid),
                    $contribution->getMetric(),
                    new Varint($contribution->getAmount()),
                    new Varint($contribution->getScore())
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
            'from' => null,
            'to' => null,
            'type' => '',
            'limit' => 1000,
            'offset' => null
        ], $options);


        $cql = "SELECT * from contributions";
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

        if ($options['type']) {
            $where[] = 'metric = ?';
            $values[] = (string) $options['type'];
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

        $contributions = [];

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }

        foreach($rows as $row) {
            $contribution = new Contribution();
            $contribution
                ->setUser((string) $row['user_guid'])
                ->setMetric((string) $row['metric'])
                ->setTimestamp($row['timestamp']->time() * 1000)
                ->setAmount((string) BigNumber::_($row['amount']))
                ->setScore((int) $row['score']);

            $contributions[] = $contribution;
        }

        return [
            'contributions' => $contributions,
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

    public function sum($options)
    {

    }


}
