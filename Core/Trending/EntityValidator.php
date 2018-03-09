<?php
namespace Minds\Core\Trending;

use Minds\Entities;

class EntityValidator
{

    public function isValid($guid, $type = null, $subtype = null, $rating = 1)
    {
        \Minds\Core\Security\ACL::$ignore = true;
        $entity = Entities\Factory::build($guid);
        if (!$entity) {
            return false;
        }

        if ($type && $type != $entity->type) {
            echo "$guid type $type is not $entity->type";
            return false;
        }

        if ($subtype && $subtype != $entity->subtype) {
            echo "$guid type $type is not $entity->type";
            return false;
        }

        if ($entity->getRating() > $rating) {
            return false;
        }

        return $this->isEnabled($entity) && $this->isOwnerEnabled($entity->getOwnerEntity()) && !$this->isMature($entity) && !$this->isMature($entity->getOwnerEntity());
    }

    protected function isMature($entity)
    {
        $mature = false;
        if (method_exists($entity, 'getMature')) {
            $mature = $entity->getMature();
        } elseif (method_exists($entity, 'getFlag')) {
            $mature = $entity->getFlag('mature');
        }

        if ($mature) {
            return true;
        }

        return false;
    }

    protected function isEnabled($entity)
    {
        if ($entity->banned == 'yes' || $entity->enabled == 'no') {
            echo $entity->guid . "is not valid";
            return false;
        }
        return true;
    }

    protected function isOwnerEnabled($entity)
    {
        if (!$entity) {
            return true;
        }
        $entity = Entities\Factory::build($entity->guid);

        return $this->isEnabled($entity) && !$this->isMature($entity);
    }

}
