<?php
namespace Minds\Core\Payments\Plans;

use Minds\Core;
use Minds\Core\Di\Di;

class Repository
{

    private $db;
    private $config;

    private $entity_guid;
    private $user_guid;

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

    public function setUserGuid($guid)
    {
        $this->user_guid = $guid;
        return $this;
    }

    /**
     * Return all subscriptions for an entity
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
          'limit' => 10,
          'offset' => ''
        ], $opts);

        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("SELECT * FROM plans WHERE entity_guid = ?", [
            $this->entity_guid
          ]);
        try {
            $result = $this->db->request($query);
            $guids = [];
            foreach ($result as $row) {
                $guids[] = $row['guid'];
            }
            return $guids;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Return a subscription to an entity
     */
    public function getSubscription($planId)
    {
        $plan = new Plan();
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("SELECT * FROM plans
          WHERE entity_guid = ? AND plan = ? AND user_guid = ?", [
            (string) $this->entity_guid,
            (string) $planId,
            (string) $this->user_guid
          ]);
        try {
            $result = $this->db->request($query);
            $plan->setEntityGuid($result[0]['entity_guid'])
              ->setName($result[0]['plan'])
              ->setUserGuid($result[0]['user_guid'])
              ->setStatus($result[0]['status'])
              ->setExpires($result[0]['expires'])
              ->setSubscriptionId($result[0]['subscription_id']);

        } catch (\Exception $e) {

        }

        return $plan;
    }

    public function add($plan)
    {
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("INSERT INTO plans
          (entity_guid, plan, user_guid, status, subscription_id, expires)
          VALUES (?, ?, ?, ?, ?, ?)",
          [
            (string) $plan->getEntityGuid(),
            (string) $plan->getName(),
            (string) $plan->getUserGuid(),
            (string) $plan->getStatus(),
            (string) $plan->getSubscriptionId(),
            (int) $plan->getExpires(),
          ]);
        try {
            $result = $this->db->request($query);
        } catch (\Exception $e) {
        }
        return $this;
    }

    public function cancel($guid, $category)
    {
        return $this;
    }

}
