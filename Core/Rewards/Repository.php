<?php
namespace Minds\Core\Rewards;

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

    public function add($rewards)
    {
        if (!is_array($rewards)) {
            $rewards = [ $rewards ];
        }

        $requests = [];
        $template = "INSERT INTO rewards ( timestamp, user_guid, type, amount) VALUES (?,?,?,?)";
        foreach ($rewards as $reward) {
            $requests[] = [
                'string' => $template, 
                'values' => [
                    new Timestamp($reward->getTimestamp() / 1000),                    
                    new Varint($reward->getUser()->guid),
                    $reward->getType(),
                    new Varint($reward->getAmount())
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
            'limit' => 12,
            'offset' => null
        ], $options);

        $cql = "SELECT * from rewards";
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
            $where[] = 'type = ?';
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

        $rewards = [];

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
            $reward = new Reward();
            $reward
                ->setUser((int) $row['user_guid'])
                ->setType((string) $row['type'])
                ->setTimestamp($row['timestamp']->time() * 1000)
                ->setAmount((double) $row['amount']);
            $rewards[] = $reward;
        }

        return [
            'rewards' => $rewards,
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
