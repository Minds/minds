<?php


namespace Minds\Entities;


class EntitiesFactory
{
    /**
     * Build an entity based an GUID, an array or an object
     * @param  mixed  $value
     * @param  array  $options - ['cache' => bool]
     * @return Entity
     */
    public function build($value, array $options = [])
    {
        return Factory::build($value, $options);
    }
}