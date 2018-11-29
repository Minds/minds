<?php

/**
 * Vote Indexes
 *
 * @author emi
 */

namespace Minds\Core\Votes;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Indexes
{
    /** @var Client $cql */
    protected $cql;

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    public function insert($vote)
    {
        $entity = $vote->getEntity();
        $direction = $vote->getDirection();
        $actor = $vote->getActor();

        $userGuids = $entity->{"thumbs:{$direction}:user_guids"} ?: [];
        $userGuids[] = (string) $actor->guid;

        $this->setEntityList($entity->guid, $direction, array_values(array_unique($userGuids)));

        // Add to entity based indexes

        $this->addIndex("thumbs:{$direction}:entity:{$entity->guid}", $actor->guid);

        if ($entity->entity_guid) {
            $this->addIndex("thumbs:{$direction}:entity:{$entity->entity_guid}", $actor->guid);
        } elseif (isset($entity->custom_data['guid'])) {
            $this->addIndex("thumbs:{$direction}:entity:{$entity->custom_data['guid']}", $actor->guid);
        }

        // Add to actor based indexes

        $this->addIndex("thumbs:{$direction}:user:{$actor->guid}", $entity->guid);
        $this->addIndex("thumbs:{$direction}:user:{$actor->guid}:{$entity->type}", $entity->guid);

        return true;
    }

    public function remove($vote)
    {
        $entity = $vote->getEntity();
        $direction = $vote->getDirection();
        $actor = $vote->getActor();

        $userGuids = $entity->{"thumbs:{$direction}:user_guids"} ?: [];
        $userGuids = array_diff($userGuids, [ (string) $actor->guid ]);

        $this->setEntityList($entity->guid, $direction, array_values($userGuids));

        // Remove from entity based indexes

        $this->removeIndex("thumbs:{$direction}:entity:{$entity->guid}", $actor->guid);

        if ($entity->entity_guid) {
            $this->removeIndex("thumbs:{$direction}:entity:{$entity->entity_guid}", $actor->guid);
        } elseif (isset($entity->custom_data['guid'])) {
            $this->removeIndex("thumbs:{$direction}:entity:{$entity->custom_data['guid']}", $actor->guid);
        }

        // Remove from actor based indexes

        $this->removeIndex("thumbs:{$direction}:user:{$actor->guid}", $entity->guid);
        $this->removeIndex("thumbs:{$direction}:user:{$actor->guid}:{$entity->type}", $entity->guid);

        return true;
    }

    /**
     * Checks for existence
     * @param $entity
     * @param $actor
     * @param $direction
     * @return bool
     * @throws \Exception
     */
     public function exists($vote)
     {
         $entity = $vote->getEntity();
         $actor = $vote->getActor();
         $direction = $vote->getDirection();

         $guids = $entity->{"thumbs:{$direction}:user_guids"} ?: [];
 
         return in_array($actor->guid, $guids);
     }

    /**
     * @param int|string $guid
     * @param string $direction
     * @param array $value
     * @return bool|mixed
     */
    protected function setEntityList($guid, $direction, array $value)
    {
        $prepared = new Custom();
        $prepared->query("INSERT INTO entities (key, column1, value) VALUES (?, ?, ?)", [
            (string) $guid,
            "thumbs:{$direction}:user_guids",
            json_encode($value)
        ]);

        return $this->cql->request($prepared);
    }

    /**
     * @param string $index
     * @param int|string $guid
     * @return bool|mixed
     */
    protected function addIndex($index, $guid)
    {
        $prepared = new Custom();
        $prepared->query("INSERT INTO entities_by_time (key, column1, value) VALUES (?, ?, ?)", [
            $index,
            (string) $guid,
            (string) time()
        ]);

        return $this->cql->request($prepared);
    }

    /**
     * @param string $index
     * @param int|string $guid
     * @return bool|mixed
     */
    protected function removeIndex($index, $guid)
    {
        $prepared = new Custom();
        $prepared->query("DELETE FROM entities_by_time WHERE key = ? AND column1 = ?", [
            $index,
            (string) $guid
        ]);

        return $this->cql->request($prepared);
    }
}
