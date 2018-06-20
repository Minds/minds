<?php

/**
 * Factory for mapping handlers
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

use Minds\Helpers\MagicAttributes;

class Factory
{
    /**
     * @param $entity
     * @return MappingInterface
     * @throws \Exception
     */
    public function build($entity)
    {
        $type = MagicAttributes::getterExists($entity, 'getType') ?
            $entity->getType() : $entity->type;

        $subtype = MagicAttributes::getterExists($entity , 'getSubtype') ?
            $entity->getSubtype() : $entity->subtype;

        $guid = MagicAttributes::getterExists($entity, 'getGuid') ?
            $entity->getGuid() : $entity->guid;

        if (!is_object($entity) || !$type || !$guid) {
            throw new \Exception('Entity must be an object with a type and a GUID');
        }

        $handler = ucfirst($type) . (isset($subtype) && $subtype  ? ucfirst($subtype) : '');
        $handler = __NAMESPACE__ . "\\{$handler}Mapping";

        if (!class_exists($handler)) {
            $handler = ucfirst($type);
            $handler = __NAMESPACE__ . "\\{$handler}Mapping";
        }

        if (!class_exists($handler)) {
            $handler = EntityMapping::class;
        }

        /** @var MappingInterface $instance */
        $instance = new $handler();
        $instance->setEntity($entity);

        if (!($instance instanceof MappingInterface)) {
            throw new \Exception('Search mapping handler not found');
        }

        return $instance;
    }
}
