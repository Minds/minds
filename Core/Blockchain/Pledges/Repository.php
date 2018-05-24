<?php
/**
 * Token Pledges Repository
 */
namespace Minds\Core\Blockchain\Pledges;

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

    public function add($pledges)
    {
        if (!is_array($pledges)) {
            $pledges = [ $pledges ];
        }

        $requests = [];
        $template = "INSERT INTO pledges (
            phone_number_hash,
            user_guid,
            wallet_address,
            timestamp,
            amount,
            status
            ) 
            VALUES (?,?,?,?,?,?)";

        foreach ($pledges as $pledge) {
            $requests[] = [
                'string' => $template, 
                'values' => [
                    $pledge->getPhoneNumberHash(),
                    new Varint($pledge->getUserGuid()),
                    $pledge->getWalletAddress(),
                    new Timestamp($pledge->getTimestamp()),
                    new Varint($pledge->getAmount()),
                    (string) $pledge->getStatus()
                ]
            ];
        }

        $this->db->batchRequest($requests, Cassandra::BATCH_UNLOGGED);

        return $this;
    }

    public function getList($options)
    {
        $options = array_merge([
            'phone_number_hash' => null,
            'user_guid' => null,
            'wallet_address' => null,
            'status' => null,
            'limit' => 12,
            'offset' => null,
            'allowFiltering' => false,
        ], $options);

        $cql = "SELECT * from pledges";
        $where = [];
        $values = [];

        if ($options['user_guid']) {
            $where[] = 'phone_number_hash = ?';
            $values[] = new Varint($options['phone_number_hash']);
        }

        if ($options['user_guid']) {
            $where[] = 'user_guid = ?';
            $values[] = new Varint($options['user_guid']);
            $options['allowFiltering'] = true;
        }

        if ($options['wallet_address']) {
            $where[] = 'wallet_address = ?';
            $values[] = $options['wallet_address'];
            $options['allowFiltering'] = true;
        }

        if ($options['status']) {
            $where[] = 'status = ?';
            $values[] = (string) $options['status'];
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
            $pledge = new Pledge();
            $pledge
                ->setPhoneNumberHash($row['phone_number_hash'])
                ->setUserGuid($row['user_guid']->value())
                ->setWalletAddress($row['wallet_address'])
                ->setTimestamp((int) $row['timestamp']->time())
                ->setAmount((string) BigNumber::_($row['amount']->value()))
                ->setStatus((string) $row['status']);

            $pledges[] = $pledge;
        }

        return [
            'pledges' => $pledges,
            'token' => $rows->pagingStateToken()
        ];
    }

    public function get($phone_number_hash)
    {

        $cql = "SELECT * from pledges WHERE phone_number_hash = ?";
        $values = [ (string) $phone_number_hash ];

        $query = new Custom();
        $query->query($cql, $values);

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return null;
        }

        if (!$rows) {
            return null;
        }

        $row = $rows[0];

        $pledge = new Pledge();
        $pledge
            ->setPhoneNumberHash($row['phone_number_hash'])
            ->setUserGuid($row['user_guid']->value())
            ->setWalletAddress($row['wallet_address'])
            ->setTimestamp($row['timestamp'] ? (int) $row['timestamp']->time() : time())
            ->setAmount((string) BigNumber::_($row['amount']->value()))
            ->setStatus((string) $row['status']);

        return $pledge;
    }

    public function delete($phone_number_hash) {
        $cql = "DELETE FROM blockchain_transactions where phone_number_hash = ?";
        $values = [ $phone_number_hash ];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return false;
        }

        return true;
    }

}
