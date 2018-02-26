<?php
namespace Minds\Core\Rewards\Contributions;

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

    /** @var int */
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
     * Get the amount 
     */
    public function getAmount()
    {
        $query = new Custom();

        if ($this->user) {
            $query->query("SELECT SUM(amount) as amount from contributions WHERE user_guid = ? 
                AND timestamp = ?", 
                [
                    new Varint((int) $this->user->guid),
                    new Timestamp($this->timestamp / 1000)
                ]);
        } else {
            $query->query("SELECT SUM(amount) as amount from contributions_by_timestamp WHERE timestamp = ?", 
                [
                    new Timestamp($this->timestamp / 1000)
                ]);
        }

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }
        
        return (string) BigNumber::_($rows[0]['amount']);
    }

    /**
     * Get the score
     */
    public function getScore()
    {
        $query = new Custom();

        if ($this->user) {
            $query->query("SELECT SUM(score) as score from contributions WHERE user_guid = ? 
                AND timestamp = ?", 
                [
                    new Varint((int) $this->user->guid),
                    new Timestamp($this->timestamp / 1000)
                ]);
        } else {
            $query->query("SELECT SUM(score) as score from contributions_by_timestamp WHERE timestamp = ?", 
                [
                    new Timestamp($this->timestamp / 1000)
                ]);
        }

        try{
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
        }
        
        return (int) $rows[0]['score'];
    }

}
