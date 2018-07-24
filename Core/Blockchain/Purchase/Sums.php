<?php
/**
 * Token Purhcase Sums
 */
namespace Minds\Core\Blockchain\Purchase;

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

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Get the total amount
     * @return string
     */
    public function getTotalAmount()
    {
        $query = new Custom();

        $query->query("SELECT SUM(amount) as amount FROM token_purchases");
    
        try {
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }
        
        return (string) BigNumber::_($rows[0]['amount']);
    }

    /**
     * Get the total count
     * @return string
     */
    public function getTotalCount()
    {
        $query = new Custom();

        $query->query("SELECT count(*) as count FROM token_purchases");
    
        try {
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }
        
        return (string) BigNumber::_($rows[0]['count']);
    }

    /**
     * Get requested amount per phone_number_hash
     * @param string $phone_number_hash
     * @return string
     */
    public function getRequestedAmount($phone_number_hash)
    {
        $query = new Custom();

        $query->query("SELECT SUM(requested_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?", [
            $phone_number_hash
        ]);
    
        try {
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }

        return (string) BigNumber::_($rows[0]['amount']);
    }

    /**
     * Get issued amount per phone_number_hash
     * @param string $phone_number_hash
     * @return string
     */
    public function getIssuedAmount($phone_number_hash)
    {
        $query = new Custom();

        $query->query("SELECT SUM(issued_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?", [
            $phone_number_hash
        ]);
    
        try {
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }

        return (string) BigNumber::_($rows[0]['amount']);
    }

    /**
     * Get requested amount per phone_number_hash
     * @param string $phone_number_hash
     * @return string
     */
    public function getUnissuedAmount($phone_number_hash)
    {
        $query = new Custom();
    
        $query->query("SELECT SUM(requested_amount) as requested,
            SUM(issued_amount) as issued,
            FROM token_purchases 
            WHERE phone_number_hash = ?", [
            $phone_number_hash
        ]);
    
        try {
            $rows = $this->db->request($query);
        } catch(\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }

        if (!$rows) {
            return 0;
        }

        return (string) BigNumber::_($rows[0]['requested'])->sub(BigNumber::_($rows[0]['issued']));
    }

}
