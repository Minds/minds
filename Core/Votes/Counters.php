<?php

/**
 * Counters for votes
 *
 * @author emi
 */

namespace Minds\Core\Votes;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Helpers;

class Counters
{
    public static $validDirections = [ 'up', 'down' ];

    /** @var Client $cql */
    protected $cql;

    /** @var abstractCacher $cacher */
    protected $cacher;

    public function __construct($cql = null, $cacher = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->cacher = $cacher ?: Di::_()->get('Cache');
    }

    /**
     * @param mixed $entity
     * @param mixed $actor
     * @param string $direction
     * @return bool
     * @throws \Exception
     */
    public function update($vote, $value = 1)
    {
        $entity = $vote->getEntity();
        $direction = $vote->getDirection();

        // Direct entity modification
        $this->updateEntity($entity->guid, $direction, $entity->{"thumbs:{$direction}:count"} + $value);

        // Modify entity counters
        $this->updateCounter($entity->guid, $direction, $value);

        // If there's a remind, modify its counters
        if ($entity->remind_object) {
            $this->updateRemind($entity->remind_object, $direction, $value);
        }

        // If entity is an activity and there's an attached entity, modify its counters
        if ($entity->type == 'activity') {
            if ($entity->entity_guid) {
                $this->updateCounter($entity->entity_guid, $direction, $value);
            } elseif (isset($entity->custom_data['guid'])) {
                $this->updateCounter($entity->custom_data['guid'], $direction, $value);
            }
        }

        return true;
    }

    /**
     * Gets the number of votes an entity has
     * @param mixed $entity
     * @param string $direction It can be 'up' or 'down'
     * @return int
     * @throws \Exception
     */
    public function get($entity, $direction)
    {
        if (!in_array($direction, static::$validDirections)) {
            throw new \Exception('Invalid direction');
        }

        return (int) $entity->{"thumbs:{$direction}:count"};
    }

    /**
     * @param int|string $guid
     * @param string $direction
     * @param int $value
     * @return bool|mixed
     */
    protected function updateEntity($guid, $direction, $value)
    {
        $prepared = new Custom();
        $prepared->query("INSERT INTO entities (key, column1, value) VALUES (?, ?, ?)", [
            (string) $guid,
            "thumbs:{$direction}:count",
            (string) $value
        ]);

        return $this->cql->request($prepared);
    }

    /**
     * @param int|string $guid
     * @param string $direction
     * @param $value
     */
    protected function updateCounter($guid, $direction, $value)
    {
        Helpers\Counters::increment($guid, "thumbs:{$direction}", $value);
        $this->cacher->destroy("counter:{$guid}:thumbs:{$direction}");
    }

    /**
     * @param array $remind
     * @param string $direction
     * @param int $value
     */
    protected function updateRemind(array $remind, $direction, $value = 1)
    {
        if ($remind['guid']) {
            $this->updateCounter($remind['guid'], $direction, $value);
        }
        if ($remind['entity_guid']) {
            $this->updateCounter($remind['entity_guid'], $direction, $value);
        }
    }

}
