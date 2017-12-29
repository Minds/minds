<?php
namespace Minds\Core\Trending;

use Minds\Entities;

class EntityValidator
{

    public function isValid($guid)
    {
        $entity = Entities\Factory::build($guid);
        if (!$entity) {
            return false;
        }

        return !$this->isMature($entity);
    }

    protected function isMature($entity)
    {
        if (method_exists($entity, 'getMature')) {
            return $entity->getMature();
        } elseif (method_exists($entity, 'getFlag')) {
            return $entity->getFlag('mature');
        }

        return false;
    }

}
