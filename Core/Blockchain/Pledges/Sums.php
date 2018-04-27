<?php
/**
 * Token Pledges Sums
 */
namespace Minds\Core\Blockchain\Pledges;

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

        $query->query("SELECT SUM(amount) as amount FROM pledges");
    
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

        $query->query("SELECT count(*) as count FROM pledges");
    
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

}
