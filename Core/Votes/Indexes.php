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
use Minds\Core\Helpdesk\Entities\Question;
use Minds\Core\Helpdesk\Question\Repository;

class Indexes
{
    /** @var Client $cql */
    protected $cql;

    /** @var Repository $repository */
    protected $repository;

    public function __construct($cql = null, $repository = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->repository = $repository ?: Di::_()->get('Helpdesk\Question\Repository');
    }

    public function insert($vote)
    {
        $entity = $vote->getEntity();
        $direction = $vote->getDirection();
        $actor = $vote->getActor();

        $userGuids = $entity->{"thumbs:{$direction}:user_guids"} ?: [];
        $userGuids[] = (string) $actor->guid;

        $entity_id = null;
        $entity_type = null;

        if ($entity instanceof Question) {
            $entity_id = $entity->getUuid();
            $entity_type = 'question';
        } else {
            $entity_id = $entity->guid;
            $entity_type = $entity->type;
        }

        $this->setEntityList($entity, $direction, array_values(array_unique($userGuids)));

        // Add to entity based indexes

        $this->addIndex("thumbs:{$direction}:entity:{$entity_id}", $actor->guid);

        if ($entity->entity_guid) {
            $this->addIndex("thumbs:{$direction}:entity:{$entity->entity_guid}", $actor->guid);
        } elseif (isset($entity->custom_data['guid'])) {
            $this->addIndex("thumbs:{$direction}:entity:{$entity->custom_data['guid']}", $actor->guid);
        }

        // Add to actor based indexes

        $this->addIndex("thumbs:{$direction}:user:{$actor->guid}", $entity_id);
        $this->addIndex("thumbs:{$direction}:user:{$actor->guid}:{$entity_type}", $entity_id);

        return true;
    }

    public function remove($vote)
    {
        $entity = $vote->getEntity();
        $direction = $vote->getDirection();
        $actor = $vote->getActor();

        $entity_id = null;
        $entity_type = null;
        $userGuids = null;
        if ($entity instanceof Question) {
            $entity_id = $entity->getUuid();
            $entity_type = 'question';
            $userGuids = $entity->getUserGuids() ?: [];

        } else {
            $entity_id = $entity->guid;
            $entity_type = $entity->type;
            $userGuids = $entity->{"thumbs:{$direction}:user_guids"} ?: [];
        }

        $userGuids = array_diff($userGuids, [ (string) $actor->guid ]);



        $this->setEntityList($entity->guid, $direction, array_values($userGuids));

        // Remove from entity based indexes

        $this->removeIndex("thumbs:{$direction}:entity:{$entity_id}", $actor->guid);

        if ($entity->entity_guid) {
            $this->removeIndex("thumbs:{$direction}:entity:{$entity->entity_guid}", $actor->guid);
        } elseif (isset($entity->custom_data['guid'])) {
            $this->removeIndex("thumbs:{$direction}:entity:{$entity->custom_data['guid']}", $actor->guid);
        }

        // Remove from actor based indexes

        $this->removeIndex("thumbs:{$direction}:user:{$actor->guid}", $entity_id);
        $this->removeIndex("thumbs:{$direction}:user:{$actor->guid}:{$entity_type}", $entity->guid);

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
     * @param $entity
     * @param string $direction
     * @param array $value
     * @return bool|mixed
     */
    protected function setEntityList($entity, $direction, array $value)
    {
        if ($entity instanceof Question) {
            return $this->repository->update($entity->getUuid(), ["thumbs_{$direction}_user_guids" => $value]);
        } else {
            $prepared = new Custom();
            $prepared->query("INSERT INTO entities (key, column1, value) VALUES (?, ?, ?)", [
                (string) $entity->guid,
                "thumbs:{$direction}:user_guids",
                json_encode($value)
            ]);

            return $this->cql->request($prepared);
        }
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
