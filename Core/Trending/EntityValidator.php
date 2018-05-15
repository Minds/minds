<?php
namespace Minds\Core\Trending;

use Minds\Entities;

class EntityValidator
{

    public function isValid($entity, $rating = 1)
    {
        if (!$entity || !method_exists($entity, 'getRating')) {
            return false;
        }

        if ($entity->getRating() > $rating) {
            echo "..rating too high";
            return false;
        }

        return $this->isEnabled($entity) 
            && ($entity->type == 'user' || $this->isOwnerEnabled($entity->getOwnerEntity()))
            && !$this->isMature($entity);
            //&& !$this->isMature($entity->getOwnerEntity());
    }

    protected function isMature($entity)
    {
        if ($entity->type == 'user') {
            return false;
        }
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
        if ($entity->type == 'user' && ($entity->banned == 'yes' || $entity->enabled == 'no')) {
            echo "\n$entity->guid is not valid [banned: $entity->banned] [enabled: $entity->enabled]\n";
            return false;
        }
        if (method_exists($entity, 'getFlag') && $entity->getFlag('paywall') ) {
            echo "\n $entity->guid has a paywall flag"; 
            return false;
        }
        if ($entity->type == 'object' && $entity->access_id != 2) {
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
        return $this->isEnabled($entity);
    }

}
