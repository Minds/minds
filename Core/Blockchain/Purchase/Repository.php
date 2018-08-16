<?php
/**
 * Token Purchase Repository
 */

namespace Minds\Core\Blockchain\Purchase;

use Cassandra;
use Cassandra\Timestamp;
use Cassandra\Varint;
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

    public function add($purchases)
    {
        if (!is_array($purchases)) {
            $purchases = [$purchases];
        }

        $requests = [];
        $template = "INSERT INTO token_purchases (
            phone_number_hash,
            tx,
            user_guid,
            wallet_address,
            timestamp,
            requested_amount,
            issued_amount,
            status
            ) 
            VALUES (?,?,?,?,?,?,?,?)";

        foreach ($purchases as $purchase) {
            $requests[] = [
                'string' => $template,
                'values' => [
                    $purchase->getPhoneNumberHash(),
                    $purchase->getTx(),
                    new Varint($purchase->getUserGuid()),
                    $purchase->getWalletAddress(),
                    new Timestamp($purchase->getTimestamp()),
                    new Varint($purchase->getRequestedAmount()),
                    new Varint($purchase->getIssuedAmount()),
                    (string) $purchase->getStatus()
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
            'limit' => 50,
            'offset' => '',
            'allowFiltering' => false,
        ], $options);

        $cql = "SELECT * from token_purchases";
        $where = [];
        $values = [];

        if ($options['user_guid']) {
            $where[] = 'phone_number_hash = ?';
            $values[] = $options['phone_number_hash'];
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

        try {
            $rows = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }

        if (!$rows) {
            return [];
        }

        foreach ($rows as $row) {
            $purchase = new Purchase();
            $purchase
                ->setPhoneNumberHash($row['phone_number_hash'])
                ->setTx($row['tx'])
                ->setUserGuid((int) $row['user_guid']->value())
                ->setWalletAddress($row['wallet_address'])
                ->setTimestamp((int) $row['timestamp']->time())
                ->setRequestedAmount((string) BigNumber::_($row['requested_amount']->value()))
                ->setIssuedAmount((string) $row['issued_amount']->value())
                ->setStatus((string) $row['status']);

            $purchases[] = $purchase;
        }

        return [
            'purchases' => $purchases,
            'token' => $rows->pagingStateToken()
        ];
    }

    public function get($phone_number_hash, $tx)
    {

        $cql = "SELECT * from token_purchases WHERE phone_number_hash = ? and tx= ?";
        $values = [(string) $phone_number_hash, (string) $tx];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $rows = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return null;
        }

        if (!$rows) {
            return null;
        }

        $row = $rows[0];

        $purchase = new Purchase();
        $purchase
            ->setPhoneNumberHash($row['phone_number_hash'])
            ->setTx($row['tx'])
            ->setUserGuid((int) $row['user_guid']->value())
            ->setWalletAddress($row['wallet_address'])
            ->setTimestamp($row['timestamp'] ? (int) $row['timestamp']->time() : time())
            ->setRequestedAmount((string) BigNumber::_($row['requested_amount']->value()))
            ->setIssuedAmount((string) BigNumber::_($row['issued_amount']->value()))
            ->setStatus((string) $row['status']);

        return $purchase;
    }

    public function delete($phone_number_hash)
    {
        $cql = "DELETE FROM blockchain_transactions where phone_number_hash = ?";
        $values = [$phone_number_hash];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }

        return true;
    }

}
