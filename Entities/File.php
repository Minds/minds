<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Interfaces\Flaggable;

/**
 * File Entity
 * @todo Do not inherit from ElggFile
 * @package Minds\Entities\File
 * @method array getExportableValues()
 * @method mixed|null getFlag(string $flag)
 * @method File setFlag(string $flag, mixed $value)
 * @method void save(bool $index)
 * @method array getWireTotals()
 * @method mixed getWireThreshold()
 * @method File setWireThreshold(int $wire_threshold)
 * @method int getModeratorGUID()
 */
class File extends \ElggFile implements Flaggable
{
    protected $dirtyIndexes = false;
    /**
     * Initialize entity attributes
     * @return null
     */
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['flags'] = [];
        $this->attributes['wire_threshold'] = 0;
        $this->attributes['moderator_guid'] = null;
        $this->attributes['time_moderated'] = null;
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), [
            'flags',
            'wire_threshold',
            'moderator_guid',
            'time_moderated'
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

    /**
     * Returns the user who moderated
     * @return int moderator guid
     */
    public function getModeratorGuid() {
        return $this->moderator_guid;
    }

     /**
     * Sets the user who moderated
     * @param int $moderatorGuid
     * @return File
     */
    public function setModeratorGuid(int $moderatorGuid) {
        $this->moderator_guid = $moderatorGuid;
        return $this;
    }

     /**
     * Returns when the file was moderated
     * @return int time_moderated timestamp
     */
    public function getTimeModerated() {
        return $this->time_moderated;
    }

     /**
     * Sets when the file was moderated
     * @param int $timeModerated
     * @return File
     */
    public function setTimeModerated(int $timeModerated) {
        $this->time_moderated = $timeModerated;
        return $this;
    }
}
