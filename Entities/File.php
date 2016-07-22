<?php
/**
 * Minds file entity.
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace Minds\Entities;

use Minds\Interfaces\Flaggable;

class File extends \ElggFile implements Flaggable
{
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['flags'] = [];
    }

    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), [
        'flags'
      ]);
    }

    public function getFlag($flag)
    {
        if (!isset($this->attributes['flags']) || !$this->attributes['flags']) {
            return false;
        }

        return isset($this->attributes['flags'][$flag]) && !!$this->attributes['flags'][$flag];
    }

    public function setFlag($flag, $value)
    {
        if (!isset($this->attributes['flags'])) {
            $this->attributes['flags'] = [];
        }

        $this->attributes['flags'][$flag] = !!$value;
        return $this;
    }
}
