<?php

/**
 * Minds Magic Attributes Helper
 *
 * @author emi
 */

namespace Minds\Helpers;

class MagicAttributes
{
    /**
     * Returns if a class uses the Magic Attributes trait
     * @param $class
     * @return bool
     */
    public static function used($class)
    {
        return method_exists($class, '_magicAttributes');
    }

    /**
     * Returns if a setter exists
     * @param $class
     * @param $setter
     * @return bool
     */
    public static function setterExists($class, $setter)
    {
        $prop = lcfirst(preg_replace('/^set/', '', $setter));
        return method_exists($class, $setter) || (static::used($class) && property_exists($class, $prop));
    }

    /**
     * Returns if a getter exists
     * @param $class
     * @param $getter
     * @return bool
     */
    public static function getterExists($class, $getter)
    {
        $prop = lcfirst(preg_replace('/^get/', '', $getter));
        return method_exists($class, $getter) || (static::used($class) && property_exists($class, $prop));
    }
}
