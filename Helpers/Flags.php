<?php
namespace Minds\Helpers;

use Minds\Core;

class Flags
{
    public static function shouldFail($entity)
    {
        $currentUser = Core\Session::getLoggedInUserGuid();
        $owner = $entity->type == 'user' ? $entity->guid : $entity->owner_guid;

        if (
            Core\Session::isAdmin() ||
            ($currentUser && $currentUser == $owner) ||
            !$entity
        ) {
            return false;
        }

        return static::isSpam($entity) || static::isDeleted($entity);
    }

    public static function shouldDiscloseStatus($entity)
    {
        if (!$entity) {
            return false;
        }

        if (Core\Session::isAdmin()) {
            return true;
        }

        $currentUser = Core\Session::getLoggedInUserGuid();
        $owner = $entity->type == 'user' ? $entity->guid : $entity->owner_guid;

        if (($currentUser && $currentUser == $owner) && (static::isSpam($entity) || static::isDeleted($entity))) {
            return true;
        }

        return false;
    }

    public static function isSpam($entity)
    {
        if (method_exists($entity, 'getSpam')) {
            return !!$entity->getSpam();
        } else if (method_exists($entity, 'getFlag')) {
            return !!$entity->getFlag('spam');
        }

        return false;
    }

    public static function isDeleted($entity)
    {
        if (method_exists($entity, 'getDeleted')) {
            return !!$entity->getDeleted();
        } else if (method_exists($entity, 'getFlag')) {
            return !!$entity->getFlag('deleted');
        }

        return false;
    }
}
