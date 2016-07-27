<?php
namespace Minds\Helpers;

/**
 * Helper for entities operations
 */
class Entities
{
    /**
     * Builds setter method name for a certain underscore-named field
     * @param  string $key
     * @return string
     */
    public static function buildSetter($key)
    {
        $parts = explode('_', $key);
        foreach ($parts as $k => $part) {
            $parts[$k] = ucfirst($part);
        }
        $method = "set" . implode('', $parts);
        return $method;
    }

    /**
     * Builds getter method name for a certain underscore-named field
     * @param  string $key
     * @return string
     */
    public static function buildGetter($key)
    {
        $parts = explode('_', $key);
        foreach ($parts as $k => $part) {
            $parts[$k] = ucfirst($part);
        }
        $method = "get" . implode('', $parts);
        return $method;
    }
}
