<?php
/**
 * Dirty Checking support for entity-like classes
 *
 * @author emi
 */

namespace Minds\Traits;


trait DirtyChecking
{
    protected $dirtyAttributes = [];

    /**
     * @param string $attribute
     * @return $this
     */
    public function markAsDirty($attribute)
    {
        if (!in_array($attribute, $this->dirtyAttributes)) {
            $this->dirtyAttributes[] = $attribute;
        }

        return $this;
    }

    /**
     * @param string $attribute
     * @return $this
     */
    public function markAsPristine($attribute)
    {
        if (in_array($attribute, $this->dirtyAttributes)) {
            $this->dirtyAttributes = array_values(array_diff($this->dirtyAttributes, [ $attribute ]));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function markAllAsPristine()
    {
        $this->dirtyAttributes = [];
        return $this;
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function isDirty($attribute)
    {
        return in_array($attribute, $this->dirtyAttributes);
    }

    /**
     * @return array
     */
    public function getDirtyAttributes(array $filter = null)
    {
        return $this->dirtyAttributes;
    }
}
