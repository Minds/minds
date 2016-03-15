<?php
namespace Minds\Core\Notification;

use Minds\Entities;
use Minds\Helpers;
use Minds\Entities\Factory as EntitiesFactory;

class Factory
{
    use \Minds\Traits\CurrentUser;

    public static function build($data)
    {
        $entity = new Entities\Notification();
        $entity->loadFromArray($data);
        return $entity;
    }

    /**
     * Builds an array of Notification entities
     * @param  array $rows
     * @return array
     */
    public static function buildFromArray(array $rows)
    {
        $return = [];
        foreach ($rows as $guid => $row) {
            $return[] = self::build($row);
        }

        return $return;
    }
}
