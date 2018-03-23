<?php
namespace Minds\Entities;

use Minds\Interfaces\Flaggable;
use Minds\Core;

/**
 * Object Entity
 * @todo Do not inherit from ElggObject
 */
class Object extends \ElggObject implements Flaggable
{
    protected $dirtyIndexes;

    /**
     * Initialize entity attributes
     * @return null
     */
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['flags'] = [];
        $this->attributes['wire_threshold'] = 0;
        $this->attributes['rating'] = 2;
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), [
            'flags',
            'wire_threshold'
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

        if ($flag == 'deleted') {
            $this->dirtyIndexes = true;
        }

        return $this;
    }

    public function save($index = true)
    {
        if ($this->getFlag('deleted')) {
            $index = false;

            if ($this->dirtyIndexes) {
                $db = new Core\Data\Call('entities_by_time');

                foreach($this->getIndexKeys(true) as $idx) {
                    $db->removeAttributes($idx, [$this->guid], false);
                }
            }
        } else {
            if ($this->dirtyIndexes) {
                // Re-add to indexes, force as true
                $index = true;
            }
        }

        parent::save($index);

        // Allow attachment unpublishing
        if ($this->guid && $this->hidden && $this->access_id != ACCESS_PUBLIC) {
            // @todo: migrate to Prepared\Timeline()
            $db = new Core\Data\Call('entities_by_time');
            $remove = [
                //"$this->type",
                //"$this->type:$this->subtype",
                //"$this->type:$this->super_subtype",
                "$this->type:$this->super_subtype:user:$this->owner_guid",
                "$this->type:$this->subtype:user:$this->owner_guid",
            ];

            foreach($remove as $index) {
                $db->removeAttributes($index, array($this->guid), false);
            }
        }
    }

    /**
     * Returns the sum of every wire that's been made to this entity
     */
     public function getWireTotals() {
        $totals = [];
        // $totals['bitcoin'] = \Minds\Core\Wire\Counter::getSumByEntity($this->guid, 'bitcoin');
        return $totals;
    }

    /**
     * Gets wire threshold
     * @return mixed
     */
    public function getWireThreshold()
    {
        if (is_string($this->wire_threshold)) {
            return json_decode($this->wire_threshold, true);
        }

        return $this->wire_threshold;
    }

    /**
     * Sets wire threshold
     * @param $wire_threshold
     * @return $this
     */
    public function setWireThreshold($wire_threshold)
    {
        $this->wire_threshold = $wire_threshold;
        return $this;
    }
}
