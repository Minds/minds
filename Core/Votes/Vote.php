<?php
/**
 * Vote
 * @author Mark
 */
namespace Minds\Core\Votes;

use Minds\Entities\Factory;
use Minds\Entities\User;

class Vote
{
    protected $entity;
    protected $actor;
    protected $direction;

    /**
     * Sets the entity of the vote
     * @param mixed $entity
     * @return $this
     * @throws \Exception
     */
    public function setEntity($entity)
    {
        $this->entity = is_object($entity) ? $entity : Factory::build($entity);

        if (!$this->entity || !$this->entity->guid) {
            throw new \Exception('Entity not found');
        }

        return $this;
    }

    /**
     * Returns the entity of the vote
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the actor of the vote
     * @param User $actor
     * @return $this
     * @throws \Exception
     */
    public function setActor($actor)
    {
        $this->actor = $actor;

        if (!$this->actor || !$this->actor->guid) {
            throw new \Exception('Actor not found');
        }

        return $this;
    }

    /**
     * Returns the actor of the vote
     * @return User
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Sets the direction of the vote
     * @param string $direction
     * @return $this
     * @throws \Exception
     */
    public function setDirection($direction)
    {
        if (!in_array($direction, [ 'up', 'down'])) {
            throw new \Exception('Invalid direction');
        }
        $this->direction = $direction;
        return $this;
    }

    /**
     * Returns the direction of the vote
     * @return string (up/down)
     */
    public function getDirection()
    {
        return $this->direction;
    }

}