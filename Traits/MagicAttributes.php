<?php
/**
 * Magic Attributes allow classes to make use of set, get and is
 * functions automatically
 */

namespace Minds\Traits;

trait MagicAttributes
{
    /**
     * Magic attributes getter and setter.
     * NOTE: Separate method to allow detection by Exportable trait.
     * @param $name
     * @param array $args
     * @return $this|bool
     */
    protected function _magicAttributes($name, array $args = [])
    {
        if (strpos($name, 'set', 0) === 0) {
            $attribute = preg_replace('/^set/', '', $name);
            $attribute = lcfirst($attribute);
            $this->$attribute = $args[0];

            // DirtyChecking interop
            if (method_exists($this, 'markAsDirty')) {
                $this->markAsDirty($attribute);
            }

            return $this;
        } elseif (strpos($name, 'get', 0) === 0) {
            $attribute = preg_replace('/^get/', '', $name);
            $attribute = lcfirst($attribute);

            return $this->$attribute;
        } elseif (strpos($name, 'is', 0) === 0) {
            $attribute = preg_replace('/^is/', '', $name);
            $attribute = lcfirst($attribute);

            return (bool) $this->$attribute;
        } elseif (strpos($name, 'has', 0) === 0) {
            return (bool) $this->$name;
        }

        return $this;
    }

    /**
     * Magic method for getter and setters.
     * @param string $name - the function name
     * @param array $args - the arguments sent to the function
     * @return mixed
     */
    public function __call($name, array $args = [])
    {
        return $this->_magicAttributes($name, $args);
    }
}
