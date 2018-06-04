<?php

/**
 * Minds Legacy Entities Compatibility
 *
 * @author emi
 */

namespace Minds\Entities;

use Minds\Helpers\Text;

abstract class LegacyEntityCompat
{
    /**
     * Getter
     * @param $name
     * @return int|null
     */
    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'type':
            case 'guid':
            case 'access_id':
            case 'owner_guid':
            case 'container_guid':
                $prop = Text::camel($name);
                return isset($this->$prop) ? $this->$prop : null;
        }

        trigger_error("$name is not defined in " . get_class($this), E_USER_NOTICE);
        return null;
    }

    /**
     * isset() for getter
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        switch (strtolower($name)) {
            case 'type':
            case 'guid':
            case 'access_id':
            case 'owner_guid':
            case 'container_guid':
                return true;
        }

        return false;
    }
}
