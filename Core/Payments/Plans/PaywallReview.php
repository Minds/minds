<?php
namespace Minds\Core\Payments\Plans;

use Minds\Core;
use Minds\Entities;
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

        $cql = "SELECT * FROM entities_by_time WHERE key = 'paywall:review' ";
        $vars = [];

        if ($opts['offset']) {
            $cql .= " AND column1 < ? ";
            $vars[] = $opts['offset'];
        }
        
        $cql .= " ORDER BY column1 DESC LIMIT ?";
        $vars[] = (int) $opts['limit'];

        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query($cql, $vars);

        try {
            $result = $this->db->request($query);
            $guids = [];
            foreach ($result as $row) {
                $guids[] = $row['column1'];
            }
            return $guids;
        } catch (\Exception $e) {
            var_dump($e); exit;
            return [];
        }
    }

    public function add()
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

    public function remove()
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("DELETE FROM entities_by_time
            WHERE key = 'paywall:review' AND column1 = ?",
            [
                (string) $this->entity_guid
            ]
        );
        
        $result = $this->db->request($query);
        return $this;
    }

    public function demonetize()
    {
        if (!$this->entity_guid) {
            throw new \Exception('Invalid entity GUID');
        }

        $entity = Entities\Factory::build($this->entity_guid);

        if (!$entity || !$entity->type) {
            throw new \Exception('Invalid entity');
        }

        if ($entity->subtype == 'blog') {
            $entity->monetized = false;
            $entity->save();
        } else {
            $entity->paywall = false;
            $entity->save();
        }

        $this->remove();

        return true;
    }
}
