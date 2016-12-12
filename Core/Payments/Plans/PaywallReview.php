<?php
namespace Minds\Core\Payments\Plans;

use Minds\Core;
use Minds\Core\Di\Di;

class PaywallReview
{

    private $db;
    private $config;

    private $entity_guid;

    public function __construct($db = NULL, $config = NULL)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function setEntityGuid($guid)
    {
        $this->entity_guid = $guid;
        return $this;
    }

    /**
     * Return all paywalled content
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
          'limit' => 10,
          'offset' => ''
        ], $opts);

        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("SELECT * FROM entities_by_time WHERE key = 'paywall:review' LIMIT ?", [
            (int) $opts['limit']
          ]);
        try {
            $result = $this->db->request($query);
            $guids = [];
            foreach ($result as $row) {
                $guids[] = $row['column1'];
            }
            return $guids;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function add($plan)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("INSERT INTO entities_by_time
          (key, column1, value)
          VALUES ('paywall:review', ?, ?)",
          [
            (string) $this->entity_guid,
            (string) $this->entity_guid
          ]);
        try {
            $result = $this->db->request($query);
        } catch (\Exception $e) {
          var_dump($e); exit;
        }
        return $this;
    }

}
