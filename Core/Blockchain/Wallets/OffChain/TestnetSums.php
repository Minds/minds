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

class TestnetSums
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

        if (!$this->user) {
            throw new \Exception('User is not set');
        }
        
        $query->query("SELECT 
            SUM(amount) as balance 
            FROM blockchain_transactions_by_address
            WHERE user_guid = ?
            AND wallet_address = 'offchain'", 
            [
                new Varint((int) $this->user->guid)
            ]);
        $query->setOpts([
            'consistency' => \Cassandra::CONSISTENCY_ALL
        ]);

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

}
