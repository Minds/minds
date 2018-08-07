<?php
namespace Minds\Core\Blockchain\Wallets\OffChain;

use Cassandra;
use Cassandra\Varint;
use Cassandra\Timestamp;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Sums
{
    /** @var Client */
    private $db;

    /** @var User */
    private $user;

    /** @var int $timestamp */
    private $timestamp;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    public function setTimestamp($ts)
    {
        $this->timestamp = $ts;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the balance
     */
    public function getBalance()
    {
        $query = new Custom();

        if ($this->user) {
            $query->query("SELECT 
                SUM(amount) as balance 
                FROM blockchain_transactions_mainnet_by_address
                WHERE user_guid = ?
                AND wallet_address = 'offchain'", 
                [
                    new Varint((int) $this->user->guid)
                ]);
            $query->setOpts([
                'consistency' => \Cassandra::CONSISTENCY_ALL
            ]);
        } else {
            //$query->query("SELECT SUM(amount) as balance from rewards");
        }

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }
        
        return (string) BigNumber::_($rows[0]['balance']);
    }


    public function getContractBalance($contract = '', $onlySpend = false)
    {
        $cql = "SELECT SUM(amount) as balance from blockchain_transactions_mainnet WHERE user_guid = ? AND wallet_address = ?";
        $values = [
            new Varint($this->user->guid),
            'offchain',
        ];

        if ($this->timestamp) {
            $cql .= " AND timestamp >= ?";
            $values[] = new Timestamp($this->timestamp);
        }

        if ($contract) {
            $cql .= " AND contract = ?";
            $values[] = (string) $contract;
        }

        if ($onlySpend) {
            $cql .= " AND amount < 0 ALLOW FILTERING";
        }

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $rows = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }

        return (string) BigNumber::_($rows[0]['balance']);
    }

}
