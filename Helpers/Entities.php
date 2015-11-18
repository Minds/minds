<?php
/**
 * Entities helper
 */
namespace Minds\Helpers;

class Entities
{
    public static function buildSetter($key)
    {
        $parts = explode('_', $key);
        foreach ($parts as $k => $part) {
            $parts[$k] = ucfirst($part);
        }
        $method = "set" . implode('', $parts);
        return $method;
    }

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
