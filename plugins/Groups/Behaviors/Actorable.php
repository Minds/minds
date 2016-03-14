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
        if ($actor && (!is_object($actor) || !($actor instanceof User))) {
            $actor = EntitiesFactory::build($actor);
        }

        $this->actor = $actor;

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
    public function canActorRead($entity = null)
    {
        return $this->hasActor() && $this->acl->read($entity, $this->actor);
    }

    /**
     * Returns true if the actor can write onto specified entity
     * @param  mixed $entity
     * @return boolean
     */
    public function canActorWrite($entity = null)
    {
        return $this->hasActor() && $this->acl->write($entity, $this->actor);
    }
}
