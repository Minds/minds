<?php
/**
 * Minds entity traits
 */
namespace Minds\Traits;

use Minds\Core;

trait Entity{

    /**
     * Return the Guid of an entity
     */
    public function getGuid()
    {
        if(!$this->guid)
            $this->guid = Core\Guid::build();
        $this->time_created = time();
        return $this->guid;
    }

}
