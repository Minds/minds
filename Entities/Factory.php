<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;

/**
 * Build an entity based on an array, object or guid
 */
class Factory
{
    /**
     * Build the entity
     * @param mixed $value
     * @return Entity
     */
    public static function build($value)
    {
        if (is_numeric($value)) {
            $db = new Data\Call('entities');
            $row = $db->getRow($value);
            $row['guid'] = $value;
        } elseif (is_object($value) || is_array($value)) {
            $row = $value;
        } elseif (is_string($value)) {
            $row = json_decode($value, true);
        } else {
            return false;
        }
        return Core\Entities::build((object) $row);
    }
}
