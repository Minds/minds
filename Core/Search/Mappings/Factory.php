<?php

/**
 * Factory for mapping handlers
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

class Factory
{
    /**
     * @param $entity
     * @return MappingInterface
     * @throws \Exception
     */
    public function build($entity)
    {
        if (!is_object($entity) || !$entity->type || !$entity->guid) {
            throw new \Exception('Entity must be an object with a type and a GUID');
        }

        $handler = ucfirst($entity->type) . (isset($entity->subtype) && $entity->subtype  ? ucfirst($entity->subtype) : '');
        $handler = __NAMESPACE__ . "\\{$handler}Mapping";

        if (!class_exists($handler)) {
            $handler = ucfirst($entity->type);
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
