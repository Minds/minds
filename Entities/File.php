<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Interfaces\Flaggable;

/**
 * File Entity
 * @todo Do not inherit from ElggFile
 */
class File extends \ElggFile implements Flaggable
{
    /**
     * Initialize entity attributes
     * @return null
     */
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['flags'] = [];
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), [
            'flags'
        ]);
    }

    /**
     * Gets a flag value. Null if not found.
     * @param  string $flag
     * @return mixed|null
     */
    public function getFlag($flag)
    {
        if (!isset($this->attributes['flags']) || !$this->attributes['flags']) {
            return false;
        }

        return isset($this->attributes['flags'][$flag]) && !!$this->attributes['flags'][$flag];
    }

    /**
     * Sets a flag value.
     * @param  string $flag
     * @param  mixed  $value
     * @return $this
     */
    public function setFlag($flag, $value)
    {
        if (!isset($this->attributes['flags'])) {
            $this->attributes['flags'] = [];
        }

        $this->attributes['flags'][$flag] = !!$value;
        return $this;
    }

    public function save($index = true)
    {
        parent::save($index);

        // Allow attachment unpublishing
        if ($this->guid && $this->hidden && $this->access_id != ACCESS_PUBLIC) {
            // @todo: migrate to Prepared\Timeline()
            $db = new Core\Data\Call('entities_by_time');
            $remove = [
                "$this->type",
                "$this->type:$this->subtype",
                "$this->type:$this->super_subtype",
                "$this->type:$this->super_subtype:user:$this->owner_guid",
                "$this->type:$this->subtype:user:$this->owner_guid",
            ];

            foreach($remove as $index) {
                $db->removeAttributes($index, array($this->guid), false);
            }
        }
    }
}
