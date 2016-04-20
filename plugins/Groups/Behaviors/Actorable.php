<?php
namespace Minds\Plugin\Groups\Behaviors;

use Minds\Core\Security\ACL;
use Minds\Entities\User;
use Minds\Entities\Factory as EntitiesFactory;

trait Actorable
{
    protected $actor;
    protected $acl;

    /**
     * Sets the ACL instance. Useful for testing.
     * @param ACL $acl
     */
    protected function setAcl($acl = null)
    {
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * Sets the actor. It'll be built onto a User instance.
     * @param mixed $actor
     */
    public function setActor($actor = null)
    {
        $this->actor = $this->toUser($actor);

        return $this;
    }

    /**
     * Gets the actor.
     * @return User
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Gets the actor GUID.
     * @return User
     */
    public function getActorGuid()
    {
        return $this->actor ? $this->actor->guid : null;
    }

    /**
     * Returns true if we have an actor.
     * @return boolean
     */
    public function hasActor()
    {
        return (bool) $this->getActor();
    }

    /**
     * Returns true if the actor can read the specified entity
     * @param  mixed $entity
     * @return boolean
     */
    public function canActorRead($entity)
    {
        return $this->hasActor() && $this->acl->read($entity, $this->actor);
    }

    /**
     * Returns true if the actor can write onto specified entity
     * @param  mixed $entity
     * @return boolean
     */
    public function canActorWrite($entity)
    {
        return $this->hasActor() && $this->acl->write($entity, $this->actor);
    }

    /**
     * Returns true if the actor can write onto specified entity, affecting (negatively) a user.
     * @param  mixed $user
     * @param  mixed $entity
     * @return boolean
     */
    public function canActorActUponUser($user, $entity, $self_allowed = true)
    {
        return ($self_allowed && $this->isActorUser($user)) || $this->canActorWrite($entity);
    }

    /**
     * Compares actor and a user's GUIDs
     * @param  User    $user
     * @return boolean
     */
    public function isActorUser($user = null)
    {
        if (!$user || !$this->hasActor()) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        if (!$user_guid) {
            return false;
        }

        return $user_guid == $this->getActorGuid();
    }

    /**
     * Internal function. Builds an User entity;
     * @param  mixed $user
     * @return User|null
     */
    protected function toUser($user = null)
    {
        if ($user && (!is_object($user) || !($user instanceof User))) {
            $user = EntitiesFactory::build($user);
        }

        return $user;
    }
}
