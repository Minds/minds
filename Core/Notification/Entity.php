<?php
/**
 * Notifications handler for entities
 */
namespace Minds\Core\Notification;

use Minds\Core\Data\Relationships;

class Entity {
    protected $guid;
    protected $db;

    public function __construct($entity, $db = null)
    {
        if (is_object($entity) && method_exists($entity, 'getGuid')) {
            $this->guid = $entity->getGuid();
        } elseif (is_object($entity)) {
            $this->guid = $entity->guid;
        } elseif (is_array($entity)) {
            $this->guid = $entity['guid'];
        } else {
            $this->guid = $entity;
        }

        $this->db = $db ?: new Relationships();
    }

    /**
     * Gets the GUIDs of users who are muted
     * @return array
     */
    public function getMutedUsers()
    {
        if (!$this->guid) {
            return [];
        }

        // TODO: [emi] Ask Mark to modify Data\Relationships or apply DI
        $rows = (new \Minds\Core\Data\Call('relationships'))->getRow("{$this->guid}:entity:muted:inverted");
        if (!$rows) {
            return [];
        }

        return array_keys($rows);
    }

    /**
     * Returns if a member has the entity muted
     * @param  mixed $user
     * @return boolean
     */
    public function isMuted($user)
    {
        if (!$this->guid || !$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        return $this->db->check($user_guid, 'entity:muted', $this->guid);
    }

    /**
     * Adds an user to the muted Index list
     * @param  mixed $user
     * @return boolean
     */
    public function mute($user)
    {
        if (!$this->guid) {
            throw new \Exception('Entity not found');
        }

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        $done = $this->db->create($user_guid, 'entity:muted', $this->guid);

        return (bool) $done;
    }

    /**
     * Removes an user from the muted Index list
     * @param  mixed $user
     * @return boolean
     */
    public function unmute($user)
    {
        if (!$this->guid) {
            throw new \Exception('Entity not found');
        }

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        $done = $this->db->remove($user_guid, 'entity:muted', $this->guid);

        return (bool) $done;
    }
}
