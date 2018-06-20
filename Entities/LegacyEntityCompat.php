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
     * @return mixed
     */
    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'type':
            case 'subtype':
            case 'super_subtype':
            case 'guid':
            case 'access_id':
            case 'owner_guid':
            case 'container_guid':
            case 'hidden':
                $prop = Text::camel($name);
                return isset($this->$prop) ? $this->$prop : null;
            case 'thumbs:up:user_guids':
                return isset($this->votesUp) ? $this->votesUp : [];
            case 'thumbs:down:user_guids':
                return isset($this->votesDown) ? $this->votesDown : [];
            case 'ownerobj':
                return isset($this->ownerObj) ? $this->ownerObj : [];
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
            case 'subtype':
            case 'super_subtype':
            case 'guid':
            case 'access_id':
            case 'owner_guid':
            case 'container_guid':
            case 'hidden':
            case 'thumbs:up:user_guids':
            case 'thumbs:down:user_guids':
            case 'ownerobj':
                return true;
        }

        return false;
    }

    /**
     * Gets the entity's owner entity
     * @param bool $useOwnerObj
     * @return User|null
     * @throws \Exception
     */
    public function getOwnerEntity($useOwnerObj = true)
    {
        if (
            $useOwnerObj &&
            (
                (method_exists($this, '_magicAttributes') && property_exists($this, 'ownerObj')) ||
                method_exists($this, 'getOwnerObj')
            )
        ) {
            $ownerObj = $this->getOwnerObj();

            if ($ownerObj) {
                return new User($ownerObj, true);
            }
        } elseif ($useOwnerObj && property_exists($this, 'ownerObj') && $this->ownerObj) {
            return new User($this->ownerObj, true);
        } elseif (
            (method_exists($this, '_magicAttributes') && property_exists($this, 'ownerGuid')) ||
            method_exists($this, 'getOwnerGuid')
        ) {
            $ownerGuid = $this->getOwnerGuid();

            if ($ownerGuid) {
                return new User($ownerGuid, false);
            }
        } elseif (property_exists($this, 'ownerGuid') && $this->ownerGuid) {
            return new User($this->ownerGuid, false);
        }

        return null;
    }
}
