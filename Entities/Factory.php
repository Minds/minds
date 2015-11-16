<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;

/**
 * Build an entity based on an array, object or guid
 */
class Factory
{

    private static $entitiesCache = [];

    /**
     * Build the entity
     * @param mixed $value
     * @return Entity
     */
    public static function build($value, array $options = [])
    {

        $options = array_merge([ 'cache'=> true ], $options);

        $canBeCached = false;

        if (is_numeric($value)) {

            if ($options['cache'] && isset(self::$entitiesCache[$value]))
                return self::$entitiesCache[$value];

            $canBeCached = true;

            $db = new Data\Call('entities');
            $row = $db->getRow($value);
            $row['guid'] = $value;

        } elseif (is_object($value) || is_array($value)) {

            // TODO: [emi] Check with Mark if we can just read ->guid and if not empty we'll load from cache
            $row = $value;

        } elseif (is_string($value)) {

            // TODO: [emi] Check with Mark if we can just read ->guid and if not empty we'll load from cache
            $row = json_decode($value, true);

        } else {
            return false;
        }

        $entity = Core\Entities::build((object) $row);

        if ($options['cache'] && $canBeCached && $entity)
            self::$entitiesCache[$value] = $entity;

        return $entity;

    }
}
