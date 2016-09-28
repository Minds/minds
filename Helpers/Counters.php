<?php
namespace Minds\Helpers;

use Minds\Core;

/**
 * Helper for metric counters
 * @todo Avoid static and use proper DI (check $client at methods)
 */
class Counters
{
    /**
     * Increment a metric count
     * @param  Entity|number $entity
     * @param  string        $metric
     * @param  int           $value  - Value to increment. Defaults to 1.
     * @param  Data\Client   $client - Database. Defaults to Cassandra.
     * @return void
     */
    public static function increment($entity, $metric, $value = 1, $client = null)
    {
        if (is_numeric($entity) || is_string($entity)) {
            $guid = $entity;
            //error_log($guid);
        } else {
            if ($entity->guid) {
                $guid = $entity->guid;
            } else {
                return null;
            }
        }
        if (!$client) {
            $client = Core\Data\Client::build('Cassandra');
        }
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try {
            $client->request($query->update($guid, $metric, $value));
        } catch (\Exception $e) {
        }
        //error_log("$guid:$metric:$value");
        $cacher = Core\Data\cache\factory::build();
        //$cacher->destroy("counter:$guid:$metric");
    }

    /**
     * Decrements a metric count
     * @param  Entity|number $entity
     * @param  string        $metric
     * @param  int           $value  - Value to increment. Defaults to 1.
     * @param  Data\Client   $client - Database. Defaults to Cassandra.
     * @return void
     */
    public static function decrement($entity, $metric, $value = 1, $client = null)
    {
        if (is_numeric($entity) || is_string($entity)) {
            $guid = $entity;
        } else {
            $guid = $entity->guid;
        }
        $value = $value * -1; //force negative
        try {
            if (!$client) {
                $client =Core\Data\Client::build('Cassandra');
            }
            $query = new Core\Data\Cassandra\Prepared\Counters();
            $client->request($query->update($guid, $metric, $value));

            $cacher = Core\Data\cache\factory::build();
            //$cacher->destroy("counter:$guid:$metric");
        } catch (\Exception $e) {
        }
    }

    /**
     * Increment metric count on several entities
     * BUT DON'T ACTUALLY BATCH BECAUSE CASSANDRA MOANS
     * @param  array         $entities
     * @param  string        $metric
     * @param  int           $value  - Value to increment. Defaults to 1.
     * @param  Data\Client   $client - Database. Defaults to Cassandra.
     * @return void
     */
    public static function incrementBatch($entities, $metric, $value = 1, $client = null)
    {
        if (!$client) {
            $client = Core\Data\Client::build('Cassandra');
        }
        $query = new Core\Data\Cassandra\Prepared\Counters();
        foreach ($entities as $entity) {
            if (is_numeric($entity) || is_string($entity)) {
                $client->request($query->update($entity, $metric, $value));
            } elseif ($entity->guid) {
                $client->request($query->update($entity->guid, $metric, $value));
                if ($entity->remind_object && isset($entity->remind_object['guid'])) {
                    $client->request($query->update($entity->remind_object['guid'], $metric, $value));
                }

                if ($entity->owner_guid) {
                    $client->request($query->update($entity->owner_guid, $metric, $value));
                }
            }
        }
        try {
            $client->request($prepared);
        } catch (\Exception $e) {
            error_log("exception in batch increment " . $e->getMessage());
        }
    }

    /**
     * Returns the count for a single metric on an entity
     * @param  Entity|number  $entity
     * @param  string         $metric
     * @param  boolean        $cache  - use a cache for result?
     * @param  Data\Client    $client - Database. Defaults to Cassandra.
     * @return int
     */
    public static function get($entity, $metric, $cache = true, $client = null)
    {
        $cacher = Core\Data\cache\factory::build();
        if (is_numeric($entity) || is_string($entity)) {
            $guid = $entity;
        } else {
            $guid = $entity->guid;
        }
        $cached = $cacher->get("counter:$guid:$metric");
        if ($cached !== false && $cache) {
            return (int) $cached;
        }
        if (!$client) {
            $client = Core\Data\Client::build('Cassandra');
        }
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try {
            $result = $client->request($query->get($guid, $metric));
            if (isset($result[0]) && isset($result[0]['count'])) {
                $count = $result[0]['count'];
            } else {
                $count =  0;
            }
        } catch (\Exception $e) {
            return 0;
        }
        $cacher->set("counter:$guid:$metric", $count, 360); //cache for 10 minutes
        return (int) $count;
    }

    /**
     * Resets a metric counter for an entity
     * @param  Entity|number  $entity
     * @param  string         $metric
     * @param  number         $value  - Resetted value. Defaults to 0.
     * @param  Data\Client    $client - Database. Defaults to Cassandra.
     * @return int
     */
    public static function clear($entity, $metric, $value = 0, $client = null)
    {
        if (is_numeric($entity) || is_string($entity)) {
            $guid = $entity;
        } else {
            if ($entity->guid) {
                $guid = $entity->guid;
            } else {
                return null;
            }
        }
        if (!$client) {
            $client =Core\Data\Client::build('Cassandra');
        }
        $query = new Core\Data\Cassandra\Prepared\Counters();
        //$client->request($query->clear($guid, $metric));
        $count = self::get($entity, $metric, false, $client);
        self::decrement($entity, $metric, $count, $client);
    }
}
