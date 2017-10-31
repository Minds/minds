<?php

/**
 * Minds Search mappings interface
 *
 * @author emi
 */

namespace Minds\Core\Search\Mappings;

/**
 * Interface MappingInterface
 * @package Minds\Core\Search\Mappings
 */
interface MappingInterface
{
    /**
     * Sets the entity to be mapped
     *
     * @param $entity
     * @return $this
     */
    public function setEntity($entity);

    /**
     * Gets the search type for the map
     *
     * @return string
     */
    public function getType();

    /**
     * Gets the search id for the map
     *
     * @return string
     */
    public function getId();

    /**
     * Gets the ES mappings
     *
     * @return array
     */
    public function getMappings();

    /**
     * Performs the map to array procedure
     *
     * @param array $defaultValues
     * @return array
     */
    public function map(array $defaultValues = []);

    /**
     * @param array $defaultValues
     * @return array
     */
    public function suggestMap(array $defaultValues = []);
}
