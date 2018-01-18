<?php
/**
 * Magic Attributes allow classes to make use of set, get and is
 * functions automatically
 */
namespace Minds\Traits;

trait MagicAttributes
{

   /**
     * Magic method for getter and setters.
     * @param string $name - the function name
     * @param array $args - the arguments sent to the function
     * @return mixed
     */
    public function __call($name, array $args = [])
    {
        if (strpos($name, 'set', 0) === 0) {
            $attribute = str_replace('set', '', $name);
            $attribute = lcfirst($attribute);
            $this->$attribute = $args[0];
            return $this;
        }
        if (strpos($name, 'get', 0) === 0) {
            $attribute = str_replace('get', '', $name);
            $attribute = lcfirst($attribute);
            return $this->$attribute;
        }
        if (strpos($name, 'is', 0) === 0) {
            $attribute = str_replace('is', '', $name);
            $attribute = lcfirst($attribute);
            return (bool) $this->$attribute;
        }
        return $this;
    }
    
}