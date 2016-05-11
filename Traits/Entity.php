<?php
/**
 * Minds entity traits
 */
namespace Minds\Traits;

use Minds\Core;

trait Entity
{
    /**
     * Return the Guid of an entity
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->guid = Core\Guid::build();
        }
        if(!$this->time_created){
            $this->time_created = time();
        }
        return $this->guid;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this->guid;
    }
}
